<?php

namespace Database\Factories;

use App\Models\Adisyon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdisyonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Adisyon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deleted_at' => fake()->dateTime(),
            'unique_id' => fake()->word(),
            'date' => fake()->date(),
            'staff_ids' => fake()->word(),
            'message' => fake()->sentence(20),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
            'branch_id' => \App\Models\Branch::factory(),
        ];
    }
}
