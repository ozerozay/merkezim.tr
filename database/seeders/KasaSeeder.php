<?php

namespace Database\Seeders;

use App\Models\Kasa;
use App\Tenant;
use Illuminate\Database\Seeder;

class KasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::first()->run(function () {
            Kasa::create([
                'branch_id' => 1,
                'name' => 'MERKEZ KASA',
            ]);

            Kasa::create([
                'branch_id' => 1,
                'name' => 'İŞ BANKASI',
            ]);

            Kasa::create([
                'branch_id' => 2,
                'name' => 'merkezzzz',
            ]);

            Kasa::create([
                'branch_id' => 2,
                'name' => 'POS',
            ]);
        });

    }
}
