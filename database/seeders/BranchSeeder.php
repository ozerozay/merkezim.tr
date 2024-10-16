<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Peren;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'name' => 'BAKIRKÖY',
            'opening_hours' => Peren::opening_hours(),
        ]);
        Branch::create([
            'name' => 'MECİDİYEKÖY',
            'opening_hours' => Peren::opening_hours(),
        ]);
    }
}
