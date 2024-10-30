<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique_id' => fake()->text(9),
            'date' => fake()->date(),
            'status' => fake()->word(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'price_real' => fake()->randomNumber(1),
            'staffs' => [],
            'freeze_date' => fake()->date(),
            'sale_no' => fake()->randomNumber(0),
            'message' => fake()->sentence(20),
            'expire_date' => fake()->date(),
            'coupons' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'branch_id' => \App\Models\Branch::factory(),
            'sale_type_id' => \App\Models\SaleType::factory(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
        ];
    }
}
