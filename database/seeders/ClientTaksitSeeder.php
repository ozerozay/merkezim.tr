<?php

namespace Database\Seeders;

use App\Models\ClientTaksit;
use Illuminate\Database\Seeder;

class ClientTaksitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientTaksit::factory()
            ->count(5)
            ->create();
    }
}
