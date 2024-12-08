<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
use App\Enum\SettingsType;
use App\Models\Appointment;
use App\Models\Kasa;
use App\Models\Talep;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;

class DefaultQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::asDefault(function ($query) {
            $pages = collect([
                SpotlightResult::make()
                    ->setTitle('Anasayfa')
                    ->setGroup('pages')
                    ->setAction('jump_to', ['path' => route('admin.index')])
                    ->setIcon('home'),
            ]);

            if (SpotlightCheckPermission::run(PermissionType::page_randevu)) {
                if (count(auth()->user()->staff_branches) > 1) {
                    $pages->push(SpotlightResult::make()
                        ->setTitle('Randevu')
                        ->setSubtitle('Randevularınızı görüntüleyin, yeni randevu oluşturun.')
                        ->setGroup('pages')
                        ->setTokens(['page_appointment' => new Appointment])
                        ->setIcon('calendar-days'), );
                }
            }

            if (SpotlightCheckPermission::run(PermissionType::page_approve)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Onay - 4 İşlem')
                    ->setSubtitle('Onay bekleyen işlemleri görüntüleyin.')
                    ->setGroup('pages')
                    ->setTokens(['kasa' => new Kasa])
                    ->setIcon('check-circle'), );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_kasa)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Kasa')
                    ->setSubtitle('Gelir-gider işlemleri')
                    ->setGroup('pages')
                    ->setTokens(['kasa' => new Kasa])
                    ->setIcon('banknotes'), );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_agenda)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Ajanda')
                    ->setSubtitle('')
                    ->setGroup('pages')
                    ->setTokens(['kasa' => new Kasa])
                    ->setIcon('calendar'), );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_talep)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Talep')
                    ->setGroup('pages')
                    ->setTokens(['page_talep' => new Talep])
                    ->setIcon('hand-thumb-up'), );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_reports)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Raporlar')
                    ->setGroup('pages')
                    ->setTokens(['kasa' => new Kasa])
                    ->setIcon('chart-bar'), );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_statistics)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('İstatistikler')
                    ->setGroup('pages')
                    ->setTokens(['kasa' => new Kasa])
                    ->setIcon('chart-pie'), );
            }

            $pages->push(SpotlightResult::make()
                ->setTitle('Çıkış')
                ->setGroup('profile')
                ->setAction('jump_to', ['path' => route('logout')])
                ->setIcon('lock-open'), );

            $users = User::query()
                ->where('name', 'like', "%{$query}%")
                ->take(5)
                ->get()
                ->map(function (User $user) {
                    return SpotlightResult::make()
                        ->setTitle($user->name.' - '.$user->client_branch->name)
                        ->setGroup('clients')
                        ->setImage(asset('kahri.png'))
                        //->setAction('jump_to', ['path' => route('admin.client.profil.index', $user->id)])
                        ->setTokens(['client' => $user])
                        ->setIcon('check');
                });
            if (SpotlightCheckPermission::run(PermissionType::action_client_create)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Danışan Oluştur')
                    ->setGroup('actions')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-client'],
                        ]));
            }

            if (SpotlightCheckPermission::run(PermissionType::admin_settings)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Ayarlar')
                    ->setGroup('pages')
                    ->setTokens(['settings' => new User])
                    ->setIcon('cog-6-tooth'));
            }

            if (SpotlightCheckPermission::run(PermissionType::page_finger)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Parmak İzi')
                    ->setGroup('pages')
                    //->setTokens(['settings' => new User])
                    ->setIcon('finger-print'));
            }

            $general_settings = \App\Actions\Spotlight\Actions\Settings\GetGeneralSettings::run();
            //dump($general_settings);
            if ($general_settings->get(SettingsType::website_active->name)) {
                if (SpotlightCheckPermission::run(PermissionType::website_settings)) {
                    $pages->push(SpotlightResult::make()
                        ->setTitle('Site Ayarları')
                        ->setGroup('site_settings')
                        ->setTokens(['websitesettings' => new User])
                        ->setIcon('cog-6-tooth'));
                    $pages->push(SpotlightResult::make()
                        ->setTitle('Online Mağaza Ayarları')
                        ->setGroup('site_settings')
                        ->setTokens(['websiteshopsettings' => new User])
                        ->setIcon('cog-6-tooth'));
                }
            }

            $pages->push(SpotlightResult::make()
                ->setTitle('Renk Modunu Değiştir')
                ->setGroup('actions')
                ->setIcon('plus-circle')
                ->setAction('dispatch_event',
                    ['name' => 'mary-toggle-theme',
                        'data' => ['component' => 'actions.create-client'],
                    ]));

            $pages = $pages->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return collect()->merge($pages)->merge($users);
        });
    }
}
