<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(1)
            ->create([
                'email' => 'admin@admin.com',
                'password' => \Hash::make('admin'),
            ]);

        $this->call(BranchSeeder::class);
        $this->call(KasaSeeder::class);
        $this->call(NoteSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(PackageItemSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ServiceCategorySeeder::class);
        $this->call(ServiceRoomSeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(OfferSeeder::class);
        $this->call(OfferItemSeeder::class);
    }
}
