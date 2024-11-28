<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
use App\Models\Appointment;
use App\Models\ClientService;
use App\Models\ClientTaksit;
use App\Models\Note;
use App\Models\Offer;
use App\Models\Sale;
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
            if (SpotlightCheckPermission::run(PermissionType::client_profil_sale)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Satış')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Satışları görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'sale' => new Sale])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_appointment)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Randevu')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Randevuları görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'appointment' => new Appointment])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_service)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Hizmet')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'clientService' => new ClientService])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_taksit)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Taksit')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Taksitleri görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'taksit' => new ClientTaksit])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_offer)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Teklif')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Teklifleri görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'offer' => new Offer])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_note)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Notlar')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Notları görüntüle')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'note' => new Note])
                    ->setIcon('arrow-right'));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_sale)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Satış Yap')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-sale',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
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
            if (SpotlightCheckPermission::run(PermissionType::action_create_coupon)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Kupon Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('receipt-percent')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-coupon',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_create_taksit)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Taksit Oluştur')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-taksit',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_product_sale)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Ürün Sat')
                    ->setGroup('client_actions_new')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-client-product-sale',
                                'arguments' => [
                                    'client' => $clientToken->getParameter('id')]],
                        ]));
            }

            return collect()->merge($pages->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result
                ) => str($result->title())->lower()->contains(str($query)->lower()));
            }));
        });
    }
}
