<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'category_id' => 1,
            'name' => 'KOLTUK ALTI',
            'seans' => 1,
            'duration' => 10,
            'price' => 150,
        ]);

        Service::create([
            'category_id' => 1,
            'name' => 'GENİTAL',
            'seans' => 1,
            'duration' => 10,
            'price' => 150,
        ]);

        Service::create([
            'category_id' => 1,
            'name' => 'ALT BACAK',
            'seans' => 1,
            'duration' => 10,
            'price' => 150,
        ]);

        Service::create([
            'category_id' => 1,
            'name' => 'ÜST BACAK',
            'seans' => 1,
            'duration' => 10,
            'price' => 150,
        ]);
    }
}
