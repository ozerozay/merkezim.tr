<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            'gender' => true,
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
            'name' => 'action_client_offer',
            'description' => 'Teklif Oluştur',
            'route' => 'admin.actions.client_offer_customer',
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

        /* Permission::create([
             'name' => 'settings',
             'description' => 'Ayarlar',
             'route' => '',
             'visible' => false,
         ]);

         Permission::create([
             'name' => 'defination',
             'description' => 'Tanımlamalar',
             'route' => '',
             'visible' => false,
         ]);

         Permission::create([
             'name' => 'defination_branch',
             'description' => 'Şube Tanımlama',
             'route' => 'admin.settings.defination.branch',
             'visible' => true,
         ]);

         Permission::create([
             'name' => 'defination_category',
             'description' => 'Kategori Tanımlama',
             'route' => 'admin.settings.defination.category',
             'visible' => true,
         ]);

         Permission::create([
             'name' => 'defination_room',
             'description' => 'Oda Tanımlama',
             'route' => 'admin.settings.defination.room',
             'visible' => true,
         ]);

         Permission::create([
             'name' => 'defination_kasa',
             'description' => 'Kasa Tanımlama',
             'route' => 'admin.settings.defination.kasa',
             'visible' => true,
         ]);

         Permission::create([
             'name' => 'defination_service',
             'description' => 'Hizmet Tanımlama',
             'route' => 'admin.settings.defination.service',
             'visible' => true,
         ]);

         Permission::create([
             'name' => 'defination_package',
             'description' => 'Paket Tanımlama',
             'route' => 'admin.settings.defination.package',
             'visible' => true,
         ]);

         Permission::create([
             'name' => 'defination_product',
             'description' => 'Ürün Tanımlama',
             'route' => 'admin.settings.defination.product',
             'visible' => true,
         ]);

         Permission::create([
             'name' => 'defination_staff',
             'description' => 'Personel Tanımlama',
             'route' => 'admin.settings.defination.staff',
             'visible' => true,
         ]);*/

        $adminRole->syncPermissions(Permission::all());
    }
}
