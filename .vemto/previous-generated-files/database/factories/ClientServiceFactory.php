<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ClientService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total' => fake()->randomFloat(2, 0, 9999),
            'remaining' => fake()->randomNumber(0),
            'gift' => fake()->boolean(),
            'message' => fake()->sentence(20),
            'status' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'service_id' => \App\Models\Service::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'client_id' => \App\Models\User::factory(),
            'sale_id' => \App\Models\Sale::factory(),
            'package_id' => \App\Models\Package::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
