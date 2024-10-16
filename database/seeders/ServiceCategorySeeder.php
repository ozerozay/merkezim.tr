<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Tenant;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::first()->run(function () {
            ServiceCategory::create([
                'branch_ids' => [1, 2],
                'name' => 'EPİLASYON',
                'price' => 0,
                'earn' => 0,
            ]);

            ServiceCategory::create([
                'branch_ids' => [1, 2],
                'name' => 'CİLT BAKIMI',
                'price' => 0,
                'earn' => 0,
            ]);
        });

    }
}
