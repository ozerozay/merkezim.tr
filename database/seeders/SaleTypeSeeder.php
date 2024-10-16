<?php

namespace Database\Seeders;

use App\Models\SaleType;
use App\Tenant;
use Illuminate\Database\Seeder;

class SaleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::first()->run(function () {
            SaleType::create([
                'name' => 'İNTERNET',
                'active' => true,
            ]);

            SaleType::create([
                'name' => 'İKİNCİ ÜYELİK',
                'active' => true,
            ]);
        });

    }
}
