<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\StaffMuhasebe;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffMuhasebeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StaffMuhasebe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => fake()->randomFloat(2, 0, 9999),
            'date' => fake()->date(),
            'type' => fake()->word(),
            'message' => fake()->sentence(20),
            'muhasebe_id' => fake()->randomNumber(),
            'muhasebe_type' => fake()->text(255),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
