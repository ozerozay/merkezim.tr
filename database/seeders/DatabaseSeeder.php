<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TenantSeeder::class,
            BranchSeeder::class,
            RoleSeeder::class,
            ServiceCategorySeeder::class,
            SaleTypeSeeder::class,
            ServiceSeeder::class,
            KasaSeeder::class,
            PackageSeeder::class,
            IlSeed::class,
        ]);
    }
}
