<?php

namespace Database\Factories;

use App\Models\Mahsup;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahsupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mahsup::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deleted_at' => fake()->dateTime(),
            'date' => fake()->date(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'message' => fake()->sentence(20),
            'giris_kasa_id' => \App\Models\Kasa::factory(),
            'cikis_kasa_id' => \App\Models\Kasa::factory(),
            'user_id' => \App\Models\User::factory(),
            'transaction_giris_id' => \App\Models\Transaction::factory(),
            'transaction_cikis_id' => \App\Models\Transaction::factory(),
        ];
    }
}
