<?php

namespace Database\Seeders;

use App\Enum\SettingsType;
use App\Models\Branch;
use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (\App\Tenant::all() as $tenant) {
            $tenant->run(function () {
                Settings::create([
                    'data' => [
                        SettingsType::site_name->name => 'MARGE GÜZELLİK',
                    ],
                ]);

                foreach (Branch::all() as $branch) {
                    Settings::create([
                        'branch_id' => $branch->id,
                        'data' => [
                            SettingsType::client_page_seans->name => true,
                            SettingsType::client_page_seans_show_zero->name => true,
                            SettingsType::client_page_seans_show_category->name => true,
                            SettingsType::client_page_seans_add_seans->name => true,
                            SettingsType::client_page_appointment->name => true,
                            SettingsType::client_page_taksit->name => true,
                            SettingsType::client_page_offer->name => true,
                            SettingsType::client_page_coupon->name => true,
                            SettingsType::client_page_referans->name => true,
                            SettingsType::client_page_package->name => true,
                            SettingsType::client_page_earn->name => true,
                            SettingsType::client_page_fatura->name => true,
                            SettingsType::client_page_support->name => true,
                        ],
                    ]);
                }
            });
        }
    }
}
