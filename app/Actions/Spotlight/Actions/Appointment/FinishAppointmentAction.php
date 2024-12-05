<?php

namespace App\Actions\Spotlight\Actions\Appointment;

use App\AppointmentStatus;
use App\Models\Appointment;
use App\Models\ClientService;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class FinishAppointmentAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $appointment = Appointment::find($info['id']);

            $appointment->status = AppointmentStatus::finish;
            $appointment->finish_user_id = $info['finish_user_id'];
            $appointment->finish_service_ids = $info['service_ids'];
            $appointment->save();

            $appointment->appointmentStatuses()->create([
                'status' => AppointmentStatus::finish,
                'message' => $info['message'],
                'user_id' => $info['user_id'],
            ]);

            $exitedValues = array_diff($appointment->service_ids, $info['service_ids']);
            $enteredValues = array_diff($info['service_ids'], $appointment->service_ids);

            $enteredServices = ClientService::whereIn('id', $enteredValues)->get();
            $exitedValues = ClientService::whereIn('id', $exitedValues)->get();

            foreach ($enteredServices as $enteredService) {
                $enteredService->remaining -= 1;
                $enteredService->save();
            }

            foreach ($exitedValues as $exitedValue) {
                $exitedValue->remaining += 1;
                $exitedValue->save();
            }

            $totalServices = ClientService::whereIn('id', $info['service_ids'])->get();

            foreach ($totalServices as $totalService) {
                $totalService->clientServiceUses()->create([
                    'user_id' => $info['user_id'],
                    'client_id' => $appointment->client_id,
                    'seans' => 1,
                    'message' => $appointment->date.' tarihli randevu kullanıldı.',
                ]);
            }

        });
    }
}
