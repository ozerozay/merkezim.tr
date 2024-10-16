<?php

namespace Database\Seeders;

use App\Models\OfferItem;
use Illuminate\Database\Seeder;

class OfferItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfferItem::factory()
            ->count(5)
            ->create();
    }
}
