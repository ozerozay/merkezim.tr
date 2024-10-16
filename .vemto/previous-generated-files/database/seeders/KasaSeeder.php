<?php

namespace Database\Seeders;

use App\Models\Kasa;
use Illuminate\Database\Seeder;

class KasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kasa::factory()
            ->count(5)
            ->create();
    }
}
