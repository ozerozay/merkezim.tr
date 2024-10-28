<?php

namespace Database\Seeders;

use App\Models\Mahsup;
use Illuminate\Database\Seeder;

class MahsupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mahsup::factory()
            ->count(5)
            ->create();
    }
}
