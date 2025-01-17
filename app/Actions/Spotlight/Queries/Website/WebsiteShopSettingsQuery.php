<?php

namespace App\Actions\Spotlight\Queries\Website;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class WebsiteShopSettingsQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('websiteshopsettings', function (SpotlightScopeToken $websiteshopsettingsToken, $query) {

            $results = collect();
            $results->push(
                SpotlightResult::make()
                    ->setTitle('Geri Dön')
                    ->setGroup('backk')
                    ->setIcon('arrow-left')
                    ->setAction('return_action'),
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Satışta Olan Paketler')
                ->setGroup('definations')
                ->setIcon('hand-thumb-up')
                ->setAction('dispatch_event',
                    ['name' => 'slide-over.open',
                        'data' => ['component' => 'settings.shop.shop-package-settings'],
                    ]));

            $results->push(SpotlightResult::make()
                ->setTitle('Satışta Olan Hizmetler')
                ->setGroup('definations')
                ->setIcon('hand-thumb-up')->setAction('dispatch_event',
                    ['name' => 'slide-over.open',
                        'data' => ['component' => 'settings.shop.shop-service'],
                    ]));

            $results = $results->sortBy(function (SpotlightResult $result, int $key) {
                return $result->title();
            });

            $results = $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return $results;
        });
    }
}
