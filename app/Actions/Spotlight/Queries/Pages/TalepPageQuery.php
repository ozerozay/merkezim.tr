<?php

namespace App\Actions\Spotlight\Queries\Pages;

use App\Models\Talep;
use App\TalepStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\Spotlight;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;

class TalepPageQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('page_talep', function ($query) {
            $results = collect();

            $results->push([
                SpotlightResult::make()
                    ->setTitle('Geri Dön')
                    ->setGroup('backk')
                    ->setIcon('arrow-left')
                    ->setAction('return_action'),
            ]);

            foreach (auth()->user()->staff_branch as $branch) {
                $talep_group_count = Talep::query()
                    ->where('branch_id', $branch->id)
                    ->selectRaw('COUNT(*) as count, id, branch_id, status, date')
                    ->groupBy('status')
                    ->toBase()
                    ->get()
                    ->pluck('count', 'status')
                    ->toArray();

                Spotlight::registerGroup($branch->name, $branch->name);

                $allStatus = array_column(TalepStatus::cases(), 'name');

                $statuses = array_merge(array_fill_keys($allStatus, 0), $talep_group_count);

                $statusesWithLabels = collect(TalepStatus::cases())->mapWithKeys(function ($status) use ($statuses) {
                    return [
                        $status->value => [
                            'count' => $statuses[$status->value] ?? 0, // Sayımı al, yoksa 0 ata
                            'label' => $status->label(), // Label'i getir
                        ],
                    ];
                });

                $results->push(
                    SpotlightResult::make()
                        ->setTitle('Tüm talepleri görüntüle')
                        ->setGroup($branch->name)
                        ->setTokens(['page_talep' => new Talep, 'pab' => $branch])
                        ->setAction('jump_to', ['path' => route('admin.talep', ['branch' => $branch->id])])
                        ->setIcon('check-circle')
                );

                foreach ($statusesWithLabels as $key => $gc) {
                    $results->push(
                        SpotlightResult::make()
                            ->setTitle($gc['label'].' - '.$gc['count'])
                            ->setGroup($branch->name)
                            ->setAction('jump_to', ['path' => route('admin.talep', ['branch' => $branch->id])])
                            ->setIcon('check-circle')
                    );
                }
            }

            return $results;
        });
    }
}
