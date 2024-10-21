<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\AdisyonService;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdisyonServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdisyonService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deleted_at' => fake()->dateTime(),
            'total' => fake()->randomFloat(2, 0, 9999),
            'gift' => fake()->word(),
            'adisyon_id' => \App\Models\Adisyon::factory(),
            'service_id' => \App\Models\Service::factory(),
        ];
    }
}
