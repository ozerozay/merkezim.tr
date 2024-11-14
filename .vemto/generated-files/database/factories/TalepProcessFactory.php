<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\TalepProcess;
use Illuminate\Database\Eloquent\Factories\Factory;

class TalepProcessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TalepProcess::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->word(),
            'message' => fake()->sentence(20),
            'date' => fake()->date(),
            'deleted_at' => fake()->dateTime(),
            'talep_id' => \App\Models\Talep::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
