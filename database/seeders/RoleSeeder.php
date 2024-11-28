<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Tenant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::first()->run(function () {
            $adminRole = Role::create([
                'name' => 'admin',
            ]);

            Role::create([
                'name' => 'staff',
            ]);

            $user = User::create([
                'branch_id' => 2,
                'phone' => '5056277636',
                'country' => '90',
                'phone_code' => '1111',
                'name' => 'CİHAT ÖZER ÖZAY',
                'tckimlik' => '34222447480',
                'adres' => 'asdasda',
                'unique_id' => '123456789',
                'gender' => true,
                'first_login' => false,
                'birth_date' => date('Y-m-d'),
                'staff_branches' => [1, 2],
            ]);

            $user->assignRole('admin');

            Permission::create([
                'name' => 'action_client_add_note',
                'description' => 'Danışan - Not Al',
                'route' => 'admin.actions.client_note_add',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_create_offer',
                'description' => 'Teklif Oluştur',
                'route' => 'admin.actions.client_create_offer',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_create_taksit',
                'description' => 'Taksit Oluştur',
                'route' => 'admin.actions.client_create_taksit',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_create',
                'description' => 'Danışan Oluştur',
                'route' => 'admin.actions.client_create',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_create_appointment',
                'description' => 'Randevu Oluştur',
                'route' => 'admin.actions.client_create_appointment',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_sale',
                'description' => 'Satış Oluştur',
                'route' => 'admin.actions.client_sale_create',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_create_service',
                'description' => 'Hizmet Yükle',
                'route' => 'admin.actions.client_create_service',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_use_service',
                'description' => 'Hizmet Kullandır',
                'route' => 'admin.actions.client_use_service',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_transfer_service',
                'description' => 'Hizmet Aktar',
                'route' => 'admin.actions.client_transfer_service',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_add_label',
                'description' => 'Danışan Etiket',
                'route' => 'admin.actions.client_add_label',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_product_sale',
                'description' => 'Danışan Ürün Sat',
                'route' => 'admin.actions.client_product_sale',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_create_reminder',
                'description' => 'Hatırlatma Oluştur',
                'route' => 'admin.actions.create_reminder',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_create_payment_tracking',
                'description' => 'Ödeme Takip Oluştur',
                'route' => 'admin.actions.create_payment_tracking',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'clients',
                'description' => 'Danışanlar',
                'route' => 'admin.clients',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'client_profil',
                'description' => 'Danışan Profil',
                'route' => 'admin.client.profil',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'action_adisyon_create',
                'description' => 'Adisyon',
                'route' => 'admin.actions.adisyon_create',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'kasa_mahsup',
                'description' => 'Mahsup',
                'route' => 'admin.kasa.mahsup',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'kasa_make_payment',
                'description' => 'Ödeme Yap',
                'route' => 'admin.kasa.make_payment',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_close_appointment',
                'description' => 'Randevu Ekranı Kapat',
                'route' => 'admin.actions.close_appointment',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_create_coupon',
                'description' => 'Kupon Oluştur',
                'route' => 'admin.actions.create_coupon',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'action_client_tahsilat',
                'description' => 'Tahsilat',
                'route' => 'admin.actions.client_tahsilat',
                'visible' => true,
            ]);

            /* ------------------------------- */

            Permission::create([
                'name' => 'change_sale_price',
                'description' => 'Satış Tutarını Değiştirebilme',
                'route' => '',
                'visible' => false,
            ]);

            /* ------------------------------- */

            Permission::create([
                'name' => 'client_profil_note',
                'description' => 'Danışan Profil - Not',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_offer',
                'description' => 'Danışan Profil - Teklif',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_service',
                'description' => 'Danışan Profil - Hizmet',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_coupon',
                'description' => 'Danışan Profil - Kupon',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_taksit',
                'description' => 'Danışan Profil - Taksit',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_sale',
                'description' => 'Danışan Profil - Satış',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_product',
                'description' => 'Danışan Profil - Ürün',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_appointment',
                'description' => 'Danışan Profil - Randevu',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'client_profil_adisyon',
                'description' => 'Danışan Profil - Adisyon',
                'route' => '',
                'visible' => false,
            ]);

            /* ------------------------------- */

            Permission::create([
                'name' => 'page_kasa',
                'description' => 'Kasa',
                'route' => 'admin.kasa',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'page_agenda',
                'description' => 'Ajanda',
                'route' => 'admin.agenda',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'page_randevu',
                'description' => 'Randevu',
                'route' => 'admin.appointment',
                'visible' => true,
            ]);

            Permission::create([
                'name' => 'page_kasa_detail',
                'description' => 'Kasa - Detay',
                'route' => 'admin.kasa.detail',
                'visible' => false,
            ]);

            /* ------------------------------- */

            Permission::create([
                'name' => 'note_delete',
                'description' => 'Not Silebilme',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'offer_process',
                'description' => 'Teklif - Düzenleme, Silme, Onaylama',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'service_process',
                'description' => 'Hizmet - Düzenleme, Silme, Aktarma',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'taksit_process',
                'description' => 'Taksit - Düzenleme, Silme, Aktarma',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'sale_process',
                'description' => 'Satış - Düzenleme, Silme, Aktarma',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'sale_product_process',
                'description' => 'Ürün Satış - Düzenleme, Silme, Aktarma',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'kasa_detail_process',
                'description' => 'Kasa İşlemi - Düzenleme, Silme, Aktarma',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'appointment_process',
                'description' => 'Randevu - Düzenleme',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'adisyon_process',
                'description' => 'Adisyon - Silme',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'admin_definations',
                'description' => 'Tanımlamalar',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'page_reports',
                'description' => 'Raporlar',
                'route' => '',
                'visible' => false,
            ]);

            Permission::create([
                'name' => 'page_statistics',
                'description' => 'İstatistikler',
                'route' => '',
                'visible' => false,
            ]);

            $adminRole->syncPermissions(Permission::all());
        });

    }
}
