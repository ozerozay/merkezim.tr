<?php

namespace Database\Factories;

use App\Models\Approve;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApproveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Approve::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->word(),
            'message' => fake()->sentence(20),
            'status' => fake()->word(),
            'approve_message' => fake()->word(),
            'data' => [],
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'approved_by' => \App\Models\User::factory(),
        ];
    }
}
