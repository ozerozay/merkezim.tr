<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_ids' => [],
            'gender' => \Arr::random(['male', 'female', 'other']),
            'name' => fake()->name(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'active' => fake()->boolean(),
            'buy_time' => fake()->numberBetween(0, 127),
            'deleted_at' => fake()->dateTime(),
        ];
    }
}
