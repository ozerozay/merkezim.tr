<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class SettingsQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('settings', function (SpotlightScopeToken $settingsToken, $query) {

            $results = collect();

            if (SpotlightCheckPermission::run(PermissionType::admin_definations)) {
                $results->push(SpotlightResult::make()
                    ->setTitle('Şubeler')
                    ->setGroup('definations')
                    ->setIcon('building-storefront'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Kasalar')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Hizmet Kategorileri')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Hizmet Odaları')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Hizmetler')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Paketler')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Ürünler')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
            }

            $results = $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return $results;
        });
    }
}
