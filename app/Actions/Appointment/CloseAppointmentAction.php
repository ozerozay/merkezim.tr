<?php

namespace App\Actions\Appointment;

use App\AppointmentStatus;
use App\AppointmentType;
use App\Exceptions\AppException;
use App\Models\Appointment;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CloseAppointmentAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $info['start_date'] = Carbon::createFromFormat('Y-m-d H:i', $info['start_date']);
            $info['end_date'] = Carbon::createFromFormat('Y-m-d H:i', $info['end_date']);

            throw_if($info['start_date']->lt(Carbon::now()), new AppException('Geçmiş tarihi kapatamazsınız.'));
            throw_if($info['end_date']->lt($info['start_date']), new AppException('Başlangıç tarihi, bitiş tarihinden önce olmalıdır.'));

            $branch = Branch::query()
                ->select('id', 'name', 'opening_hours')
                ->where('id', $info['branch_id'])
                ->first();

            throw_if(! $branch->isOpen($info['start_date']->toDateTime()) || ! $branch->isOpen($info['end_date']->toDateTime()), new AppException('Belirtilen gün veya saatte şube açık değil.'));

            $check_appointment = CheckAppointmentTimeIsAvaible::run([
                'service_room_id' => $info['service_room_id'],
                'start_date' => $info['start_date'],
                'end_date' => $info['end_date'],
            ]);

            throw_if($check_appointment, new AppException('Seçtiğiniz tarih ve saatte randevu bulunuyor.'));

            $start_date = $info['start_date'];
            $end_date = $info['end_date'];

            $appointment = Appointment::create([
                'branch_id' => $branch->id,
                'service_room_id' => $info['service_room_id'],
                'date' => $start_date->format('Y-m-d'),
                'duration' => $start_date->diffInMinutes($end_date),
                'date_start' => $start_date->format('Y-m-d H:i:s'),
                'date_end' => $end_date->format('Y-m-d H:i:s'),
                'status' => AppointmentStatus::confirmed,
                'type' => AppointmentType::close,
                'message' => $info['message'],
            ]);

            if ($appointment) {
                $appointment->appointmentStatuses()->create([
                    'user_id' => $info['user_id'],
                    'message' => 'Randevu ekranı kapatıldı.',
                    'status' => $appointment->status,
                ]);
            } else {
                throw new AppException('Randevu ekranı kapatılamadı.');
            }

            DB::commit();
        } catch (AppException $e) {
            DB::rollBack();
            throw ToastException::error($e->getMessage());
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }

    }
}
