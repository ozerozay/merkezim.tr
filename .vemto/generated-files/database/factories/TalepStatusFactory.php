<?php

namespace Database\Factories;

use App\Models\TalepStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TalepStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TalepStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'require_date' => fake()->boolean(),
            'require_client' => fake()->boolean(),
            'deleted_at' => fake()->dateTime(),
        ];
    }
}
