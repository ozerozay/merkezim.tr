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
            RoleSeeder::class,
            IlSeed::class,
            //TenantSeeder::class,
            //BranchSeeder::class,
            //ServiceCategorySeeder::class,
            //SaleTypeSeeder::class,
            //ServiceSeeder::class,
            //KasaSeeder::class,
            //PackageSeeder::class,
        ]);
    }
}
