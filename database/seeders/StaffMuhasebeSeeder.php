<?php

namespace Database\Seeders;

use App\Models\StaffMuhasebe;
use Illuminate\Database\Seeder;

class StaffMuhasebeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaffMuhasebe::factory()
            ->count(5)
            ->create();
    }
}
