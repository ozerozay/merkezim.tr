<?php

namespace App\Actions\Spotlight\Queries\Pages;

use App\AppointmentStatus;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\Spotlight;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;

class AppointmentPageQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('page_appointment', function ($query) {
            $results = collect();

            foreach (auth()->user()->staff_branch as $branch) {

                $appointments_group_count = Appointment::query()
                    ->where('branch_id', $branch->id)
                    ->whereDate('date', date('Y-m-d'))
                    ->selectRaw('COUNT(*) as count, id, branch_id, status, date')
                    ->groupBy('status')
                    ->toBase()
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray();

                //dump($appointments_group_count);
                Spotlight::registerGroup($branch->name, $branch->name);

                $allStatus = array_column(AppointmentStatus::cases(), 'name');

                $statuses = array_merge(array_fill_keys($allStatus, 0), $appointments_group_count);

                $statusesWithLabels = collect(AppointmentStatus::cases())->mapWithKeys(function ($status) use ($statuses) {
                    return [
                        $status->value => [
                            'count' => $statuses[$status->value] ?? 0, // Sayımı al, yoksa 0 ata
                            'label' => $status->label(), // Label'i getir
                        ],
                    ];
                });

                //dump($statusesWithLabels);

                $results->push(
                    SpotlightResult::make()
                        ->setTitle('Tüm randevuları görüntüle')
                        ->setGroup($branch->name)
                        ->setTokens(['page_appointment' => new Appointment, 'pab' => $branch])
                        ->setAction('jump_to', ['path' => route('admin.appointment', ['branch' => $branch->id])])
                        ->setIcon('check-circle')
                );

                foreach ($statusesWithLabels as $key => $gc) {
                    $results->push(
                        SpotlightResult::make()
                            ->setTitle($gc['label'].' - '.$gc['count'])
                            ->setGroup($branch->name)
                            ->setAction('jump_to', ['path' => route('admin.appointment', ['branch' => $branch->id])])
                            ->setIcon('check-circle')
                    );
                }

            }

            return $results;

        });
    }
}
