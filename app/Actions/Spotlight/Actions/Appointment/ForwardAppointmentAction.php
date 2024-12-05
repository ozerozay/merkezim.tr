<?php

namespace App\Actions\Spotlight\Actions\Appointment;

use App\AppointmentStatus;
use App\Models\Appointment;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class ForwardAppointmentAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $appointment = Appointment::find($info['id']);

            $appointment->appointmentStatuses()->create([
                'status' => AppointmentStatus::forwarded,
                'message' => $info['message'],
                'user_id' => $info['user_id'],
            ]);

            $appointment->status = AppointmentStatus::forwarded;
            $appointment->forward_user_id = $info['forward_user_id'];
            $appointment->save();
        });
    }
}
