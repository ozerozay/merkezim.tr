<?php

namespace Database\Seeders;

use App\Models\AdisyonService;
use Illuminate\Database\Seeder;

class AdisyonServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdisyonService::factory()
            ->count(5)
            ->create();
    }
}
