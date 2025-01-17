<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'route' => fake()->text(255),
            'description' => fake()->sentence(15),
            'visible' => fake()->boolean(),
            'guard_name' => fake()->text(255),
        ];
    }
}
