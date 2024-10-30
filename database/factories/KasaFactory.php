<?php

namespace Database\Factories;

use App\Models\Kasa;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class KasaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kasa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'type' => fake()->word(),
            'active' => fake()->boolean(),
            'deleted_at' => fake()->dateTime(),
            'branch_id' => \App\Models\Branch::factory(),
        ];
    }
}
