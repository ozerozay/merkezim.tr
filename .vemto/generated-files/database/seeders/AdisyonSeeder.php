<?php

namespace Database\Seeders;

use App\Models\Adisyon;
use Illuminate\Database\Seeder;

class AdisyonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Adisyon::factory()
            ->count(5)
            ->create();
    }
}
