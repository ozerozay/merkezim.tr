<?php

namespace App\Actions\Appointment;

use App\Exceptions\AppException;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class MerkezdeAppointmentAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $appointment = Appointment::find($info['id']);

            $appointment->appointmentStatuses()->create([
                'status' => $info['status'],
                'message' => $info['message'],
                'user_id' => $info['user_id'],
            ]);

            $appointment->status = $info['status'];
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
