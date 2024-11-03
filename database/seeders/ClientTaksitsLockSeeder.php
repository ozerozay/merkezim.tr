<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientTaksitsLock;

class ClientTaksitsLockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientTaksitsLock::factory()
            ->count(5)
            ->create();
    }
}
