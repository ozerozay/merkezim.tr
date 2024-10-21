<?php

namespace Database\Seeders;

use App\Models\Approve;
use Illuminate\Database\Seeder;

class ApproveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Approve::factory()
            ->count(5)
            ->create();
    }
}
