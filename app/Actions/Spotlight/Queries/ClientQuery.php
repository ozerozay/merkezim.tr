<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
use App\Models\Adisyon;
use App\Models\Appointment;
use App\Models\ClientService;
use App\Models\ClientTaksit;
use App\Models\Coupon;
use App\Models\Note;
use App\Models\Offer;
use App\Models\Sale;
use App\Models\SaleProduct;
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

            $pages->push(
                SpotlightResult::make()
                    ->setTitle('⬅️ Geri Dön')
                    ->setGroup('backk')
                    ->setAction('return_action')
            );

            if (SpotlightCheckPermission::run(PermissionType::client_profil)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🏠 Anasayfa')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Danışanın anasayfasını görüntüle')
                    ->setAction('jump_to', ['path' => route('admin.client.profil.index', ['user' => $clientToken->getParameter('id')])])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_sale)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🛒 Satış')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Satışları görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'sale' => new Sale])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_appointment)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📅 Randevu')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Randevuları görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'appointment' => new Appointment])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_service)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('💆 Hizmet')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Hizmetlerini görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'clientService' => new ClientService])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_taksit)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('💳 Taksit')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Taksitleri görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'taksit' => new ClientTaksit])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_product)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📦 Ürün Satışları')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Ürün satışlarını görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'product' => new SaleProduct])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_coupon)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🎟️ Kupon')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('İndirim kuponlarını görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'coupon' => new Coupon])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_adisyon)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🧾 Adisyon')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Adisyonları görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'adisyon' => new Adisyon])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_offer)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📜 Teklif')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Teklifleri görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'offer' => new Offer])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::client_profil_note)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📝 Notlar')
                    ->setGroup('client_actions_pages')
                    ->setSubtitle('Notları görüntüle')
                    ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'note' => new Note])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_edit_user)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('✏️ Bilgilerini Düzenle')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.edit-user',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_tahsilat)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('💰 Tahsilat')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-tahsilat',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_sale)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🛒 Satış Yap')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-sale',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_add_note)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📝 Not Al')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-client-note',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_add_label)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🏷️ Etiket Belirle')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-client-label',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_create_service)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('💇 Hizmet Yükle')
                    ->setSubtitle('Hizmet yüklemek için tıklayın')
                    ->setTypeahead(true)
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-client-service',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_use_service)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('💇 Hizmet Kullandır')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.use-client-service',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_create_offer)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📜 Teklif Oluştur')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-offer',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_adisyon_create)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🧾 Adisyon Oluştur')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-adisyon',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_create_coupon)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🎟️ Kupon Oluştur')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-coupon',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_create_taksit)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('💳 Taksit Oluştur')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-taksit',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }
            if (SpotlightCheckPermission::run(PermissionType::action_client_product_sale)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('🛍️ Ürün Sat')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-client-product-sale',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }

            if (SpotlightCheckPermission::run(PermissionType::action_send_sms)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('📨 SMS Gönder')
                    ->setGroup('client_actions_new')
                    ->setAction('dispatch_event', [
                        'name' => 'slide-over.open',
                        'data' => ['component' => 'actions.create-send-sms',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ])
                );
            }

            return collect()->merge($pages->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result
                ) => str($result->title())->lower()->contains(str($query)->lower()));
            }));
        });
    }
}
