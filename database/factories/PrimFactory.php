<?php

namespace Database\Factories;

use App\Models\Prim;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Prim::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->word(),
            'amount' => fake()->randomNumber(0),
            'active' => fake()->boolean(),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'sale_type_id' => \App\Models\SaleType::factory(),
        ];
    }
}
