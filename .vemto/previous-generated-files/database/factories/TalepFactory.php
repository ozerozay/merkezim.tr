<?php

namespace Database\Factories;

use App\Models\Talep;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TalepFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Talep::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => fake()->phoneNumber(),
            'phone_long' => fake()->text(255),
            'name' => fake()->name(),
            'date' => fake()->date(),
            'status' => fake()->word(),
            'message' => fake()->sentence(20),
            'type' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'branch_id' => \App\Models\Branch::factory(),
        ];
    }
}
