<?php

namespace App\Actions\Spotlight\Actions\Check;

use App\AppointmentStatus;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckClientActiveAppointment
{
    use AsAction;

    public function handle(): bool
    {
        try {

            return Appointment::query()
                ->where('client_id', auth()->user()->id)
                ->whereIn('status', AppointmentStatus::activeNoLate())
                ->exists();

        } catch (\Throwable $e) {
            return false;
        }
    }
}
