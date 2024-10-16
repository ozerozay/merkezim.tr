<?php

namespace Database\Seeders;

use App\Models\Prim;
use Illuminate\Database\Seeder;

class PrimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prim::factory()
            ->count(5)
            ->create();
    }
}
