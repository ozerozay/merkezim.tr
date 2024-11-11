<?php

namespace Database\Seeders;

use App\Models\TalepStatus;
use Illuminate\Database\Seeder;

class TalepStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TalepStatus::factory()
            ->count(5)
            ->create();
    }
}
