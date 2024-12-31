<?php

namespace App\Actions\Spotlight\Queries;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class StatisticQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('statistics', function (SpotlightScopeToken $statisticsToken, $query) {

            $results = collect();

            $results->push(
                SpotlightResult::make()
                    ->setTitle('Geri Dön')
                    ->setGroup('backk')
                    ->setIcon('arrow-left')
                    ->setAction('return_action'),
            );
            $results->push(SpotlightResult::make()
                ->setTitle('Danışan')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.statistics.client')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Satış')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
                ->setAction('jump_to', ['path' => route('admin.reports.sale')])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Taksit')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Hizmet')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Personel Muhasebe')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Randevu')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Kasa')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Ürün Satış')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Talep')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Zaman Tüneli')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('SMS')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Notlar')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Adisyon')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Teklif')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Kupon')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Onay')
                ->setGroup('statistics')
                ->setIcon('chart-bar')
            );

            $results = $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return $results;
        });
    }
}
