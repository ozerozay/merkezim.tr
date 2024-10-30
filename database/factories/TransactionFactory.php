<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transacable_id' => fake()->randomNumber(),
            'transacable_type' => fake()->text(255),
            'date' => fake()->date(),
            'price' => fake()->randomFloat(2, 0, 9999),
            'message' => fake()->sentence(20),
            'type' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'kasa_id' => \App\Models\Kasa::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'user_id' => \App\Models\User::factory(),
            'client_id' => \App\Models\User::factory(),
            'masraf_id' => \App\Models\Masraf::factory(),
            'transacable_type' => fake()->randomElement([
                \App\Models\Sale::class,
                \App\Models\Adisyon::class,
            ]),
            'transacable_id' => \App\Models\Sale::factory(),
        ];
    }
}
