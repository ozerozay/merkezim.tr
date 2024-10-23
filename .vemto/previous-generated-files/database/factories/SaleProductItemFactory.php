<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\SaleProductItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleProductItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleProductItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'deleted_at' => fake()->dateTime(),
            'sale_product_id' => \App\Models\SaleProduct::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
