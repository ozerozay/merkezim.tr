<?php

namespace App\Actions\Spotlight\Actions\Check;

use App\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\ServiceRoom;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\OpeningHours\OpeningHours;

class CheckAvailableAppointments
{
    use AsAction;

    public function handle($info): array|\Illuminate\Support\Collection
    {
        try {
            $branch = Branch::query()
                ->where('id', $info['branch_id'])
                ->first();

            $openingHours = OpeningHours::create($branch->opening_hours);

            $openDates = [];

            $service_rooms = ServiceRoom::query()
                ->where('branch_id', $branch->id)
                ->where('active', true)
                ->whereRelation('categories', 'id', '=', $info['category_id'])
                ->get();

            $forDates = null;

            //dump($info);
            if ($info['type'] === 'range') {
                $forDates = CarbonPeriod::create(
                    Carbon::createFromFormat('Y-m-d', $info['search_date_first']),
                    Carbon::createFromFormat('Y-m-d', $info['search_date_last'])
                );

            } else {
                $forDates = collect($info['dates'])->map(function ($d) {
                    return Carbon::createFromFormat('Y-m-d', $d);
                });
            }

            foreach ($forDates as $date) {

                $hoursForDate = $openingHours->forDate($date->toDateTime());

                $openTime = $closeTime = null;

                foreach ($hoursForDate as $hours) {
                    $openTime = $hours->start()->format();
                    $closeTime = $hours->end()->format();
                }

                $openDates[] = [
                    'date' => $date,
                    'open' => $openingHours->isOpenOn($date->format('Y-m-d')),
                    'openTime' => $openTime,
                    'closeTime' => $closeTime,
                ];
            }
            //dump($openDates);

            $openDates = collect($openDates);

            //dump($openDates);

            $roomGaps = [];
            //dump($info);
            foreach ($openDates->where('open', true)->all() as $openDate) {
                //dump($openDate);
                foreach ($service_rooms as $service_room) {
                    $appointments = Appointment::query()
                        ->select(['id', 'status', 'service_category_id', 'service_room_id', 'date', 'date_start', 'date_end'])
                        ->where('service_category_id', $info['category_id'])
                        ->where('service_room_id', $service_room->id)
                        ->whereNotIn('status', [AppointmentStatus::rejected, AppointmentStatus::cancel])
                        ->whereDate('date', $openDate['date']->format('Y-m-d'))
                        ->get();

                    $dolu = [];

                    foreach ($appointments as $appointment) {
                        $dolu[] = [
                            'baslangic' => $appointment->date_start->subMinutes(1)->format('H:i'),
                            'bitis' => $appointment->date_end->addMinutes(1)->format('H:i'),
                        ];
                    }
                    //dump($service_room->id);
                    //dump($dolu);

                    $roomGaps[] = [
                        'id' => $service_room->id,
                        'name' => $service_room->name,
                        'date' => $openDate['date']->format('Y-m-d'),
                        'gaps' => $this->findAppointmentGaps($dolu, Carbon::parse($openDate['openTime']), Carbon::parse($openDate['closeTime']), $info['duration']),
                    ];

                    //dump($roomGaps);

                }

            }

            return collect($roomGaps)->groupBy('date');
        } catch (\Throwable $e) {
            return [];
        }

    }

    public function findAppointmentGaps($dolu, $calisma_baslangic, $calisma_bitis, $aralik): ?array
    {
        $bosluklar = [];
        if (count($dolu) > 0) {
            for ($i = 0; $i < count($dolu); $i++) {
                $baslangic = Carbon::parse($dolu[$i]['baslangic']);
                $bitis = Carbon::parse($dolu[$i]['bitis']);

                if ($i == 0) {
                    if ($baslangic->diffInMinutes($calisma_baslangic) < 0) {
                        $bosluklar[] = [
                            'baslangic' => $calisma_baslangic->format('H:i'),
                            'bitis' => $baslangic->format('H:i'),
                        ];
                    }
                } else {
                    if ($baslangic->diffInMinutes(Carbon::parse($dolu[$i - 1]['bitis'])) < 0) {
                        $bosluklar[] = [
                            'baslangic' => $dolu[$i - 1]['bitis'],
                            'bitis' => $dolu[$i]['baslangic'],
                        ];
                    }
                }
            }
            $bitis = Carbon::parse($dolu[count($dolu) - 1]['bitis']);

            if ($calisma_bitis->diffInMinutes($bitis) < 0) {
                $bosluklar[] = [
                    'baslangic' => $dolu[count($dolu) - 1]['bitis'],
                    'bitis' => $calisma_bitis->format('H:i'),
                ];
            }
        } else {
            $bosluklar[] = [
                'baslangic' => $calisma_baslangic->format('H:i'),
                'bitis' => $calisma_bitis->format('H:i'),
            ];
        }

        $falan = [];
        foreach ($bosluklar as $b) {
            $falan[] = $this->splitTimeIntoIntervals(Carbon::createFromFormat('H:i', $b['baslangic']), Carbon::createFromFormat('H:i', $b['bitis']), $aralik);
        }

        $aa = [];
        foreach ($falan as $f) {
            foreach ($f as $s => $v) {
                $aa[] = $v;
            }
        }

        return $aa;
    }

    public static function splitTimeIntoIntervals($start_time, $end_time, $aralik): array
    {
        $intervals = [];

        $current_time = $start_time->copy();

        while ($current_time->lt($end_time)) {
            $next_time = $current_time->copy()->addMinutes($aralik);
            if ($next_time->gt($end_time)) {
                break;
            }
            $intervals[] = $current_time->format('H:i').'-'.$next_time->format('H:i');
            $current_time = $next_time;
        }

        return $intervals;
    }
}
