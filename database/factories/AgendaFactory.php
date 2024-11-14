<?php

namespace Database\Factories;

use App\Models\Agenda;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Agenda::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(10),
            'description' => fake()->sentence(15),
            'message' => fake()->sentence(20),
            'date' => fake()->date(),
            'start_time' => fake()->word(),
            'end_time' => fake()->word(),
            'frequency' => fake()->word(),
            'type' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
            'branch_id' => \App\Models\Branch::factory(),
        ];
    }
}
