<?php

namespace Database\Factories;

use App\Models\PackageItem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PackageItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'deleted_at' => fake()->dateTime(),
            'service_id' => \App\Models\Service::factory(),
            'service_id' => \App\Models\Package::factory(),
        ];
    }
}
