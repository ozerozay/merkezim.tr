<?php

namespace App\Actions\Spotlight\Actions\Appointment;

use App\AppointmentStatus;
use App\Models\Appointment;
use App\Models\ClientService;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class CancelAppointmentAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $appointment = Appointment::find($info['id']);

            $appointment->appointmentStatuses()->create([
                'status' => AppointmentStatus::cancel,
                'message' => $info['message'],
                'user_id' => $info['user_id'],
            ]);

            $service_ids = $appointment->service_ids;

            if ($appointment->status == AppointmentStatus::finish) {
                $service_ids = $appointment->finish_service_ids;
            }

            $clientServices = ClientService::whereIn('id', $service_ids)->get();

            foreach ($clientServices as $clientService) {
                $clientService->remaining += 1;
                $clientService->save();
                $clientService->clientServiceUses()->create([
                    'user_id' => $info['user_id'],
                    'client_id' => $appointment->client_id,
                    'seans' => 1,
                    'message' => $appointment->date.' tarihli randevu iptal edildi, geri yüklendi.',
                ]);
            }

            $appointment->status = AppointmentStatus::cancel;
            $appointment->save();
        });
    }
}
