<?php

namespace Database\Factories;

use App\Models\SaleType;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique->name(),
            'active' => fake()->boolean(),
            'deleted_at' => fake()->dateTime(),
        ];
    }
}
