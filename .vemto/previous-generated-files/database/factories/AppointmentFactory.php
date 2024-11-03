<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_ids' => fake()->word(),
            'date' => fake()->date(),
            'duration' => fake()->word(),
            'date_start' => fake()->word(),
            'date_end' => fake()->word(),
            'status' => fake()->word(),
            'message' => fake()->sentence(20),
            'finish_service_ids' => fake()->word(),
            'reservation_service_ids' => fake()->word(),
            'type' => fake()->word(),
            'deleted_at' => fake()->dateTime(),
            'client_id' => \App\Models\User::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'service_room_id' => \App\Models\ServiceRoom::factory(),
            'service_category_id' => \App\Models\ServiceCategory::factory(),
            'forward_user_id' => \App\Models\User::factory(),
            'finish_user_id' => \App\Models\User::factory(),
        ];
    }
}
