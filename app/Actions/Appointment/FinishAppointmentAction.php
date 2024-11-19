<?php

namespace App\Actions\Appointment;

use App\AppointmentStatus;
use App\Exceptions\AppException;
use App\Models\Appointment;
use App\Models\ClientService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class FinishAppointmentAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

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

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
