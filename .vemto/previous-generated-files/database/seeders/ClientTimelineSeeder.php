<?php

namespace Database\Seeders;

use App\Models\ClientTimeline;
use Illuminate\Database\Seeder;

class ClientTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientTimeline::factory()
            ->count(5)
            ->create();
    }
}
