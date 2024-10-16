<?php

namespace Database\Seeders;

use App\Models\Kasa;
use Illuminate\Database\Seeder;

class KasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kasa::create([
            'branch_id' => 1,
            'name' => 'MERKEZ KASA',
        ]);

        Kasa::create([
            'branch_id' => 1,
            'name' => 'İŞ BANKASI',
        ]);
    }
}
