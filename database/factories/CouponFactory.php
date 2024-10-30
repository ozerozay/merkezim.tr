<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->text(20),
            'discount_type' => fake()->boolean(),
            'count' => 0,
            'discount_amount' => fake()->randomNumber(0),
            'end_date' => fake()->date(),
            'min_order' => fake()->randomNumber(1),
            'deleted_at' => fake()->dateTime(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
        ];
    }
}
