<?php

namespace Database\Seeders;

use App\Models\TalepProcess;
use Illuminate\Database\Seeder;

class TalepProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TalepProcess::factory()
            ->count(5)
            ->create();
    }
}
