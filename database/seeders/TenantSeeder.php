<?php

namespace Database\Seeders;

use App\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = Tenant::create([
            'id' => 'marge',
        ]);

        $tenant->domains()->create([
            'domain' => 'marge',
        ]);
    }
}
