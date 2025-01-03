<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
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

    public function permissionList()
    {
        $permissionItems = [
            PermissionType::page_randevu->name => function () use (&$pages) {
                // Birden fazla şubesi varsa Randevu'yu push et
                if (count(auth()->user()->staff_branches) > 1) {
                    $pages->push(
                        SpotlightResult::make()
                            ->setTitle('Randevu')
                            ->setGroup('pages')
                            ->setTokens(['page_appointment' => new Appointment])
                            ->setIcon('calendar-days')
                    );
                }
            },

            PermissionType::page_approve->name => function () use (&$pages) {
                // Onay sayfasını push et
                $pages->push(
                    SpotlightResult::make()
                        ->setTitle('Onay')
                        ->setGroup('pages')
                        ->setTokens(['kasa' => new Kasa])
                        ->setIcon('check-circle')
                        ->setAction('dispatch_event', [
                            'name' => 'slide-over.open',
                            'data' => ['component' => 'modals.approve.approve-modal'],
                        ])
                );
            },
        ];
    }

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::asDefault(function ($query) {
            $pages = collect([SpotlightResult::make()
                ->setTitle('🏠 Anasayfa')
                ->setGroup('pages')
                ->setAction('jump_to', ['path' => route('admin.index')]),
            ]);

            if (SpotlightCheckPermission::run(PermissionType::page_randevu)) {
                if (count(auth()->user()->staff_branches) > 1) {
                    $pages->push(SpotlightResult::make()
                        ->setTitle('📅 Randevu')
                        ->setGroup('pages')
                        ->setTokens(['page_appointment' => new Appointment])
                    );
                }
            }

            if (SpotlightCheckPermission::run(PermissionType::page_approve)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('✔️ Onay')
                    ->setGroup('pages')
                    ->setTokens(['kasa' => new Kasa])
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'modals.approve.approve-modal'],
                    ])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_kasa)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('💵 Kasa')
                    ->setGroup('pages')
                    ->setTokens(['kasa' => new Kasa])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_agenda)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📆 Ajanda')
                    ->setSubtitle('')
                    ->setGroup('pages')
                    ->setAction('jump_to', ['path' => route('admin.agenda')])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_talep)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('👍 Talep')
                    ->setGroup('pages')
                    ->setTokens(['page_talep' => new Talep])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_reports)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📊 Raporlar')
                    ->setGroup('pages')
                    ->setTokens(['reports' => new User])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::page_statistics)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📈 İstatistikler')
                    ->setGroup('pages')
                    ->setTokens(['statistics' => new User])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::admin_settings)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('⚙️ Ayarlar')
                    ->setGroup('pages')
                    ->setTokens(['settings' => new User])
                );
            }

            $pages->push(SpotlightResult::make()
                ->setTitle('🔓 Çıkış')
                ->setGroup('profile')
                ->setAction('jump_to', ['path' => route('logout')])
            );

            $users = User::query()
                ->where('name', 'like', "%{$query}%")
                ->take(5)
                ->latest()
                ->whereHas('client_branch', function ($q) {
                    $q->whereIn('branch_id', auth()->user()->staff_branches);
                })
                ->with('client_branch:id,name')
                ->get();

            foreach ($users as $user) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('👤 '.$user->name.' - '.$user->client_branch->name)
                    ->setGroup('clients')
                    ->setTokens(['client' => $user])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::action_client_create)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('➕ Danışan Oluştur')
                    ->setGroup('actions')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-client'],
                    ])
                );
            }

            $pages->push(SpotlightResult::make()
                ->setTitle('🎨 Renk Modunu Değiştir')
                ->setGroup('actions')
                ->setAction('dispatch_event', [
                    'name' => 'mary-toggle-theme',
                    'data' => ['component' => 'actions.create-client'],
                ])
            );

            $pages->push(SpotlightResult::make()
                ->setTitle('☀️ İstanbul’da bugün 28°C - Keyifli günler!')
                ->setGroup('weather'));

            $pages = $pages->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return collect()->merge($pages);
        });
    }
}
