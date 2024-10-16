<?php

namespace Database\Factories;

use App\Models\OfferItem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OfferItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'itemable_type' => fake()->text(255),
            'itemable_id' => fake()->randomNumber(),
            'quantity' => fake()->randomNumber(),
            'offer_itemable_id' => fake()->randomNumber(),
            'offer_itemable_type' => fake()->text(255),
            'offer_id' => \App\Models\Offer::factory(),
            'offer_itemable_type' => fake()->randomElement([
                \App\Models\Service::class,
                \App\Models\Package::class,
            ]),
            'offer_itemable_id' => \App\Models\Service::factory(),
        ];
    }
}
