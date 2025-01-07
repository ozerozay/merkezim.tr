<?php

namespace App\Actions\Spotlight\Actions\Check;

use App\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\ServiceRoom;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\OpeningHours\OpeningHours;

class CheckAvailableAppointments
{
    use AsAction;

    public function handle($info): Collection
    {
        try {
            $branch = Branch::with(['serviceRooms' => function ($q) use ($info) {
                $q->where('active', true)
                    ->whereHas('categories', fn($q) => $q->where('id', $info['category_id']));
            }])
                ->select('id', 'opening_hours')
                ->findOrFail($info['branch_id']);

            $openingHours = OpeningHours::create($branch->opening_hours);

            $dates = $this->prepareDates($info);
            $openDates = $this->prepareOpenDates($dates, $openingHours);
            $appointments = $this->getAppointments($info, $openDates, $branch->serviceRooms);

            return $this->calculateGaps($openDates, $appointments, $branch->serviceRooms, $info['duration']);
        } catch (\Throwable $e) {
            return collect();
        }
    }

    private function prepareDates($info): Collection
    {
        if ($info['type'] === 'range') {
            $startDate = Carbon::createFromFormat('Y-m-d', $info['search_date_first']);
            $endDate = Carbon::createFromFormat('Y-m-d', $info['search_date_last']);

            // Maksimum 3 gün kontrolü
            if ($startDate->diffInDays($endDate) > 2) {
                $endDate = $startDate->copy()->addDays(2);
            }

            return collect(CarbonPeriod::create($startDate, $endDate));
        }

        // Multi mod için maksimum 3 gün kontrolü
        return collect($info['dates'])
            ->take(3)
            ->map(fn($d) => Carbon::createFromFormat('Y-m-d', $d));
    }

    private function prepareOpenDates($dates, $openingHours): Collection
    {
        return collect($dates)->map(function ($date) use ($openingHours) {
            $hoursForDate = $openingHours->forDate($date->toDateTime());

            $openTime = $closeTime = null;
            foreach ($hoursForDate as $hours) {
                $openTime = $hours->start()->format();
                $closeTime = $hours->end()->format();
            }

            return [
                'date' => $date,
                'open' => $openingHours->isOpenOn($date->format('Y-m-d')),
                'openTime' => $openTime,
                'closeTime' => $closeTime,
            ];
        })->filter(fn($date) => $date['open']);
    }

    private function getAppointments($info, $openDates, $serviceRooms): Collection
    {
        $dates = $openDates->pluck('date')->map(fn($date) => $date->format('Y-m-d'));

        return Appointment::query()
            ->select(['id', 'status', 'service_category_id', 'service_room_id', 'date', 'date_start', 'date_end'])
            ->where('service_category_id', $info['category_id'])
            ->whereIn('service_room_id', $serviceRooms->pluck('id'))
            ->whereNotIn('status', [AppointmentStatus::rejected, AppointmentStatus::cancel])
            ->whereIn('date', $dates)
            ->get()
            ->groupBy(['service_room_id', 'date']);
    }

    private function calculateGaps($openDates, $appointments, $serviceRooms, $duration): Collection
    {
        $gaps = [];

        foreach ($openDates as $openDate) {
            $date = $openDate['date']->format('Y-m-d');

            foreach ($serviceRooms as $room) {
                $roomAppointments = $appointments->get($room->id, collect())
                    ->get($date, collect())
                    ->map(fn($apt) => [
                        'baslangic' => $apt->date_start->subMinutes(1)->format('H:i'),
                        'bitis' => $apt->date_end->addMinutes(1)->format('H:i')
                    ])->toArray();

                $availableGaps = $this->findAppointmentGaps(
                    $roomAppointments,
                    Carbon::parse($openDate['openTime']),
                    Carbon::parse($openDate['closeTime']),
                    $duration
                );

                if (!empty($availableGaps)) {
                    $gaps[] = [
                        'id' => $room->id,
                        'name' => $room->name,
                        'date' => $date,
                        'gaps' => $availableGaps
                    ];
                }
            }
        }

        return collect($gaps)->groupBy('date');
    }

    private function findAppointmentGaps($appointments, $workStart, $workEnd, $duration): array
    {
        $gaps = [];
        $currentTime = $workStart->copy();

        if (empty($appointments)) {
            while ($currentTime->copy()->addMinutes($duration)->lte($workEnd)) {
                $gaps[] = $currentTime->format('H:i');
                $currentTime->addMinutes($duration);
            }
            return $gaps;
        }

        $appointments = collect($appointments)->sortBy('baslangic');

        $firstAppointment = Carbon::parse($appointments->first()['baslangic']);
        while ($currentTime->copy()->addMinutes($duration)->lte($firstAppointment)) {
            $gaps[] = $currentTime->format('H:i');
            $currentTime->addMinutes($duration);
        }

        foreach ($appointments as $i => $appointment) {
            if ($i < count($appointments) - 1) {
                $currentTime = Carbon::parse($appointment['bitis']);
                $nextStart = Carbon::parse($appointments[$i + 1]['baslangic']);

                while ($currentTime->copy()->addMinutes($duration)->lte($nextStart)) {
                    $gaps[] = $currentTime->format('H:i');
                    $currentTime->addMinutes($duration);
                }
            }
        }

        $currentTime = Carbon::parse($appointments->last()['bitis']);
        while ($currentTime->copy()->addMinutes($duration)->lte($workEnd)) {
            $gaps[] = $currentTime->format('H:i');
            $currentTime->addMinutes($duration);
        }

        return $gaps;
    }
}
