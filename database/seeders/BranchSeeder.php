<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\ServiceRoom;
use App\Peren;
use App\Tenant;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::first()->run(function () {
            Branch::create([
                'name' => 'BAKIRKÖY',
                'opening_hours' => Peren::opening_hours(),
            ]);
            Branch::create([
                'name' => 'MECİDİYEKÖY',
                'opening_hours' => Peren::opening_hours(),
            ]);

            ServiceRoom::create([
                'branch_id' => 1,
                'category_ids' => [1, 2],
                'name' => 'EPİLASYON 1',
                'active' => true,
            ]);

            ServiceRoom::create([
                'branch_id' => 1,
                'category_ids' => [1, 2],
                'name' => 'EPİLASYON 2',
                'active' => true,
            ]);

            ServiceRoom::create([
                'branch_id' => 2,
                'category_ids' => [1, 2],
                'name' => 'EPİLASYON 2',
                'active' => true,
            ]);

            ServiceRoom::create([
                'branch_id' => 2,
                'category_ids' => [1, 2],
                'name' => 'EPİLASYON 2',
                'active' => true,
            ]);
        });

    }
}
