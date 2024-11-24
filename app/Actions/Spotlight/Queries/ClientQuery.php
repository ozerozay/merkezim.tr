<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
use App\Models\Appointment;
use App\Models\Note;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class ClientQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('client', function ($query, SpotlightScopeToken $clientToken) {
            $pages = collect();
            /*
            if (SpotlightCheckPermission::run('client_profil_service')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Hizmet')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    ->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_sale')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Satış')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Satışları görüntüle')
                    ->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_sale')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Taksit')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_appointment')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Randevu')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_product')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Ürün')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_adisyon')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Adisyon')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Adisyonları görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_adisyon')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Adisyon')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Adisyonları görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));

            }
            if (SpotlightCheckPermission::run('client_profil_offer')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Teklif')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Teklifleri görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_coupon')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Kupon')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run('client_profil_note')) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Not')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => $clientToken->getParameter('id'), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }*/
            $pages->push(SpotlightResult::make()
                ->setTitle('Randevu')
                ->setGroup('client_actions_pages')
                ->setSubtitle('Randevuları görüntüle')
                //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'appointment' => new Appointment])
                ->setIcon('arrow-right'));

            if (SpotlightCheckPermission::run(PermissionType::action_client_add_note)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Not Al')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-client-note',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_add_label)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Etiket Belirle')
                    ->setGroup('client_actions_new')
                    ->setIcon('tag')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-client-label',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_create_service)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Hizmet Yükle')
                    ->setSubtitle('asdasd')
                    ->setTypeahead(true)
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-client-service',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_use_service)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Hizmet Kullandır')
                    ->setGroup('client_actions_new')
                    ->setIcon('minus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.use-client-service',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_create_offer)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Teklif Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-offer',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_adisyon_create)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Adisyon Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-adisyon',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            /*
            $pages->push(
                SpotlightResult::make()
                    ->setTitle('Bilgilerini Düzenle')
                    ->setGroup('client_actions_edit')
                    ->setIcon('pencil')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Şube Değiştir')
                    ->setGroup('client_actions_edit')
                    ->setIcon('building-storefront')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Hizmet Yükle')
                    ->setGroup('client_actions_service')
                    ->setIcon('building-storefront')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Hizmet Kullandır')
                    ->setGroup('client_actions_service')
                    ->setIcon('building-storefront')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Hizmet Aktar')
                    ->setGroup('client_actions_service')
                    ->setIcon('building-storefront')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Hizmet')
                    ->setGroup('client_actions_sale')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Adisyon')
                    ->setGroup('client_actions_sale')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'modal.open',
                            'data' => ['component' => 'note.add-note',
                                'key' => 'asdfsdfsdf',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Ürün Sat')
                    ->setGroup('client_actions_sale')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),

                SpotlightResult::make()
                    ->setTitle('Taksit Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Teklif Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Kupon Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Randevu Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('SMS Gönder')
                    ->setGroup('client_actions_contact')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
                SpotlightResult::make()
                    ->setTitle('Whatsapp')
                    ->setGroup('client_actions_contact')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'note.add-note',
                                'arguments' => ['user' => $clientToken->getParameter('id')]],
                        ]),
            );*/

            return collect()->merge($pages->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result
                ) => str($result->title())->lower()->contains(str($query)->lower()));
            }));
        });
    }
}
