<?php

namespace Database\Seeders;

use App\Models\ClientService;
use Illuminate\Database\Seeder;

class ClientServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientService::factory()
            ->count(5)
            ->create();
    }
}
