<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ClientTaksitsLock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientTaksitsLockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientTaksitsLock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'used' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'client_id' => \App\Models\User::factory(),
            'client_taksit_id' => \App\Models\ClientTaksit::factory(),
            'service_id' => \App\Models\Service::factory(),
        ];
    }
}
