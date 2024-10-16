<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_ids' => [],
            'name' => fake()->name(),
            'active' => fake()->boolean(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'earn' => fake()->numberBetween(0, 127),
            'deleted_at' => fake()->dateTime(),
        ];
    }
}
