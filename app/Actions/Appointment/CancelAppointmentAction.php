<?php

namespace App\Actions\Appointment;

use App\AppointmentStatus;
use App\Exceptions\AppException;
use App\Models\Appointment;
use App\Models\ClientService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CancelAppointmentAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

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

            $clientServices = ClientService::where('id', $service_ids)->get();

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

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
