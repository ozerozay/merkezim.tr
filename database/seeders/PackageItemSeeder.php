<?php

namespace Database\Seeders;

use App\Models\PackageItem;
use Illuminate\Database\Seeder;

class PackageItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PackageItem::factory()
            ->count(5)
            ->create();
    }
}
