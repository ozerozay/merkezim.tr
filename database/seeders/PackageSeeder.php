<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'branch_ids' => [1, 2],
            'name' => 'EPİLASYON TÜM VÜCUT',
            'price' => 1000,
            'buy_time' => 0,
        ]);
    }
}
