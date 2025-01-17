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
            'name' => fake()->name(),
            'message' => fake()->sentence(20),
            'date' => fake()->date(),
            'date' => fake()->date(),
            'date_create' => fake()->date(),
            'time' => fake()->date(),
            'status' => fake()->word(),
            'type' => fake()->word(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'status_message' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'talep_id' => \App\Models\Talep::factory(),
        ];
    }
}
