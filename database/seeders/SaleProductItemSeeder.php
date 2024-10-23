<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SaleProductItem;

class SaleProductItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleProductItem::factory()
            ->count(5)
            ->create();
    }
}
