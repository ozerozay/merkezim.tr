<?php

namespace Database\Seeders;

use App\Models\ServiceRoom;
use Illuminate\Database\Seeder;

class ServiceRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceRoom::factory()
            ->count(5)
            ->create();
    }
}
