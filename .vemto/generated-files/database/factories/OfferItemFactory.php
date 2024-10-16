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
            'offer_id' => \App\Models\Offer::factory(),
            'itemable_type' => fake()->randomElement([
                \App\Models\Package::class,
                \App\Models\Service::class,
            ]),
            'itemable_id' => \App\Models\Package::factory(),
        ];
    }
}
