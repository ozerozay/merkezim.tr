<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'staff_branches' => [],
            'name' => fake()->name(),
            'unique_id' => fake()->text(9),
            'referans_id' => fake()->text(9),
            'country' => fake()->country(),
            'phone' => fake()->phoneNumber(),
            'phone_code' => fake()->text(4),
            'tckimlik' => fake()->text(11),
            'birth_date' => fake()->date(),
            'adres' => fake()->text(255),
            'il' => fake()->numberBetween(0, 127),
            'ilce' => fake()->numberBetween(0, 127),
            'email' => fake()
                ->unique()
                ->safeEmail(),
            'email_verified_at' => now(),
            'password' => \Hash::make('password'),
            'gender' => \Arr::random(['male', 'female', 'other']),
            'active' => fake()->boolean(),
            'first_login' => fake()->boolean(),
            'remember_token' => \Str::random(10),
            'deleted_at' => fake()->dateTime(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
