<?php

namespace Database\Seeders;

use App\Models\Masraf;
use Illuminate\Database\Seeder;

class MasrafSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Masraf::factory()
            ->count(5)
            ->create();
    }
}
