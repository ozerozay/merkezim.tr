<?php

namespace Database\Factories;

use App\Models\Masraf;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasrafFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Masraf::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => fake()->boolean(),
            'name' => fake()->name(),
            'deleted_at' => fake()->dateTime(),
            'branch_id' => \App\Models\Branch::factory(),
        ];
    }
}
