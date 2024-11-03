<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\AppointmentStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentStatusesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AppointmentStatuses::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->sentence(20),
            'status' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'appointment_id' => \App\Models\Appointment::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
