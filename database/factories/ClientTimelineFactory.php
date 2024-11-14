<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ClientTimeline;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientTimelineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientTimeline::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->sentence(20),
            'type' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
        ];
    }
}
