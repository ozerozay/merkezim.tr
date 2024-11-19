<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'gender' => \Arr::random(['male', 'female', 'other']),
            'seans' => fake()->numberBetween(0, 127),
            'duration' => fake()->randomNumber(0),
            'price' => fake()->randomFloat(2, 0, 9999),
            'min_day' => fake()->randomNumber(0),
            'active' => fake()->boolean(),
            'visible' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'category_id' => \App\Models\ServiceCategory::factory(),
        ];
    }
}
