<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgendaOccurrence;

class AgendaOccurrenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgendaOccurrence::factory()
            ->count(5)
            ->create();
    }
}
