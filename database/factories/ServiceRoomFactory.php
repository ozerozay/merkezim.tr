<?php

namespace Database\Factories;

use App\Models\ServiceRoom;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceRoom::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_ids' => [],
            'name' => fake()->name(),
            'active' => fake()->boolean(),
            'deleted_at' => fake()->dateTime(),
            'branch_id' => \App\Models\Branch::factory(),
        ];
    }
}
