<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\ClientTaksit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientTaksitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientTaksit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total' => fake()->randomFloat(2, 0, 9999),
            'remaining' => fake()->randomNumber(1),
            'date' => fake()->date(),
            'status' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'client_id' => \App\Models\User::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'sale_id' => \App\Models\Sale::factory(),
            'client_id' => \App\Models\User::factory(),
        ];
    }
}
