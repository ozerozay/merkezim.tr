<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\AgendaOccurrence;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendaOccurrenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AgendaOccurrence::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'occurrence_date' => fake()->date(),
            'deleted_at' => fake()->dateTime(),
            'agenda_id' => \App\Models\Agenda::factory(),
        ];
    }
}
