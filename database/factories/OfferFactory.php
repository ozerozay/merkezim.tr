<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique_id' => fake()->word(),
            'expire_date' => fake()->word(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'status' => fake()->word(),
            'message' => fake()->sentence(20),
            'month' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
        ];
    }
}
