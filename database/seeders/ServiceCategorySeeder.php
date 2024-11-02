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
            $category = ServiceCategory::create([
                'branch_ids' => [1, 2],
                'name' => 'EPİLASYON',
                'price' => 0,
                'earn' => 0,
            ]);

            $category_cilt = ServiceCategory::create([
                'branch_ids' => [1, 2],
                'name' => 'CİLT BAKIMI',
                'price' => 0,
                'earn' => 0,
            ]);

            $category->services()->create([
                'name' => 'KOLTUK ALTI',
                'gender' => 0,
                'seans' => 1,
                'duration' => 10,
                'price' => 150,
                'min_day' => 0,
                'active' => true,
            ]);

            $category->services()->create([
                'name' => 'GENİTAL',
                'gender' => 0,
                'seans' => 1,
                'duration' => 10,
                'price' => 150,
                'min_day' => 0,
                'active' => true,
            ]);

            $category_cilt->services()->create([
                'name' => 'MİNİ BAKIM',
                'gender' => 0,
                'seans' => 1,
                'duration' => 60,
                'price' => 150,
                'min_day' => 0,
                'active' => true,
            ]);

            $category_cilt->services()->create([
                'name' => 'PROFESYONEL BAKIM',
                'gender' => 0,
                'seans' => 1,
                'duration' => 60,
                'price' => 150,
                'min_day' => 0,
                'active' => true,
            ]);
        });

    }
}
