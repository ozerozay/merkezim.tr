<?php

namespace Database\Seeders;

use App\Models\SaleType;
use Illuminate\Database\Seeder;

class SaleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleType::create([
            'name' => 'İNTERNET',
            'active' => true,
        ]);

        SaleType::create([
            'name' => 'İKİNCİ ÜYELİK',
            'active' => true,
        ]);
    }
}
