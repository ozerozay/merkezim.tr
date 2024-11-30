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
                    ->setTitle('Şube')
                    ->setGroup('definations')
                    ->setIcon('building-storefront'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Kasa')
                    ->setGroup('definations')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'settings.defination.kasa.kasa-defination'],
                        ]));
                $results->push(SpotlightResult::make()
                    ->setTitle('Hizmet Kategorileri')
                    ->setGroup('definations')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'settings.defination.category.category-defination'],
                        ]));
                $results->push(SpotlightResult::make()
                    ->setTitle('Hizmet Odaları')
                    ->setGroup('definations')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'settings.defination.room.room-defination'],
                        ]));
                $results->push(SpotlightResult::make()
                    ->setTitle('Hizmetler')
                    ->setGroup('definations')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'settings.defination.service.service-defination'],
                        ]));
                $results->push(SpotlightResult::make()
                    ->setTitle('Paketler')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Ürünler')
                    ->setGroup('definations')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'settings.defination.product.product-defination'],
                        ]));
                $results->push(SpotlightResult::make()
                    ->setTitle('Personel')
                    ->setGroup('definations')
                    ->setIcon('pencil'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Satış Tipi')
                    ->setGroup('definations')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'settings.defination.sale-type.sale-type-defination'],
                        ]));
                $results->push(SpotlightResult::make()
                    ->setTitle('Masraf')
                    ->setGroup('definations')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'settings.defination.masraf.masraf-defination'],
                        ]));
            }

            $results = $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return $results;
        });
    }
}
