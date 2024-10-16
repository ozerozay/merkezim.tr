<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientServiceUse;

class ClientServiceUseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientServiceUse::factory()
            ->count(5)
            ->create();
    }
}
