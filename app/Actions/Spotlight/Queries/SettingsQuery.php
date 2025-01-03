<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class SettingsQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('settings', function (SpotlightScopeToken $settingsToken, $query) {

            $results = collect();

            $results->push(
                SpotlightResult::make()
                    ->setTitle('⬅️ Geri Dön')
                    ->setGroup('backk')
                    ->setAction('return_action')
            );

            $results->push(SpotlightResult::make()
                ->setTitle('⚙️ Site Ayarları')
                ->setGroup('site_settings')
                ->setTokens(['websitesettings' => new User])
            );

            $results->push(SpotlightResult::make()
                ->setTitle('🛒 Online Mağaza Ayarları')
                ->setGroup('site_settings')
                ->setTokens(['websiteshopsettings' => new User])
            );

            if (SpotlightCheckPermission::run(PermissionType::admin_definations)) {
                $results->push(SpotlightResult::make()
                    ->setTitle('🏢 Şube')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.branch.branch-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('💵 Kasa')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.kasa.kasa-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('📂 Hizmet Kategorileri')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.category.category-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('🛋️ Hizmet Odaları')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.room.room-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('🔧 Hizmetler')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.service.service-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('📦 Paketler')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.package.package-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('🏷️ Danışan Etiketleri')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.label.label-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('📦 Ürünler')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.product.product-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('👨‍💼 Personel')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.staff.staff-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('💳 Satış Tipi')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.sale-type.sale-type-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('🧾 Masraf')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.masraf.masraf-defination'],
                    ])
                );

                $results->push(SpotlightResult::make()
                    ->setTitle('✉️ SMS Şablonu')
                    ->setGroup('definations')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'settings.defination.s-m-s-template.s-m-s-template-defination'],
                    ])
                );
            }

            $results = $results->sortBy(function (SpotlightResult $q) {
                return $q->title();
            });

            return $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });
        });
    }
}
