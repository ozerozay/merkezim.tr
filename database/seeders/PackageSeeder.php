<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Tenant;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::first()->run(function () {
            Package::create([
                'branch_id' => 1,
                'name' => 'EPİLASYON TÜM VÜCUT',
                'price' => 1000,
                'buy_time' => 0,
            ]);
        });

    }
}
