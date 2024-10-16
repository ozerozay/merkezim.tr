<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ClientServiceUse;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientServiceUseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientServiceUse::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'seans' => fake()->word(),
            'message' => fake()->sentence(20),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
            'client_service_id' => \App\Models\ClientService::factory(),
        ];
    }
}
