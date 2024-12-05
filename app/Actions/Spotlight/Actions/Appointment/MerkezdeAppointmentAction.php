<?php

namespace App\Actions\Spotlight\Actions\Appointment;

use App\Models\Appointment;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class MerkezdeAppointmentAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {

            $appointment = Appointment::find($info['id']);

            $appointment->appointmentStatuses()->create([
                'status' => $info['status'],
                'message' => $info['message'],
                'user_id' => $info['user_id'],
            ]);

            $appointment->status = $info['status'];
            $appointment->save();
        });
    }
}
