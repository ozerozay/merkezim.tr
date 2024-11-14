<?php

namespace Database\Seeders;

use App\Models\Talep;
use Illuminate\Database\Seeder;

class TalepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Talep::factory()
            ->count(5)
            ->create();
    }
}
