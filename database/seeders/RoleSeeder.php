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
                'name' => 'action_adisyon_create',
                'description' => 'Adisyon',
                'route' => 'admin.actions.adisyon_create',
                'visible' => true,
            ]);

            /* ------------------------------- */

            Permission::create([
                'name' => 'change_sale_price',
                'description' => 'Satış Tutarını Değiştirebilme',
                'route' => '',
                'visible' => true,
            ]);

            $adminRole->syncPermissions(Permission::all());
        });

    }
}
