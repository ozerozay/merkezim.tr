<?php

namespace App\Actions\Appointment;

use App\Actions\User\CheckUserInstantApprove;
use App\AppointmentStatus;
use App\AppointmentType;
use App\Exceptions\AppException;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\ClientService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateManuelAppointmentAction
{
    use AsAction;

    /**
     * @throws \Throwable
     * @throws ToastException
     */
    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $info['date'] = Carbon::parse($info['date']);

            $client = User::query()
                ->select(['id', 'name', 'branch_id'])
                ->where('id', $info['client_id'])
                ->first();

            throw_if(! $client, new AppException('Danışan bulunamadı.'));

            throw_if($info['date']->lt(Carbon::now()), new AppException('Geçmiş tarihe randevu oluşturamazsınız.'));

            $branch = Branch::query()
                ->select('id', 'name', 'opening_hours')
                ->where('id', $client->branch_id)
                ->first();

            throw_if(! $branch->isOpen($info['date']->toDateTime()), new AppException('Belirtilen gün veya saatte şube açık değil.'));

            $services = ClientService::query()
                ->select(['id', 'remaining', 'service_id', 'client_id'])
                ->whereIn('id', $info['service_ids'])
                ->with('service')
                ->get();

            $total_duration = $services->sum(function ($q) {
                return $q->service->duration;
            });

            $start_date = $info['date'];
            $end_date = $info['date']->copy()->addMinutes($total_duration);

            $check_appointment = CheckAppointmentTimeIsAvaible::run([
                'service_room_id' => $info['room_id'],
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);

            throw_if($check_appointment, new AppException('Seçtiğiniz tarih ve saatte randevu bulunuyor.'));

            $appointment = Appointment::create([
                'client_id' => $client->id,
                'branch_id' => $client->branch_id,
                'service_room_id' => $info['room_id'],
                'service_category_id' => $info['category_id'],
                'service_ids' => $info['service_ids'],
                'date' => $start_date->format('Y-m-d'),
                'duration' => $total_duration,
                'date_start' => $start_date->format('Y-m-d H:i:s'),
                'date_end' => $end_date->format('Y-m-d H:i:s'),
                'status' => CheckUserInstantApprove::run($info['user_id']) ? AppointmentStatus::waiting : AppointmentStatus::awaiting_approve,
                'type' => AppointmentType::appointment,
                'message' => $info['message'],
            ]);

            if ($appointment) {
                $appointment->appointmentStatuses()->create([
                    'user_id' => $info['user_id'],
                    'message' => 'Randevu oluşturuldu.',
                    'status' => $appointment->status,
                ]);
                foreach ($services as $service) {
                    if ($service->remaining > 0) {
                        $service->remaining -= 1;
                        $service->clientServiceUses()->create([
                            'user_id' => $info['user_id'],
                            'client_id' => $client->id,
                            'client_service_id' => $service->id,
                            'seans' => 1,
                            'message' => $start_date->format('d/m/Y H:i:s').' tarihli randevu kullanımı',
                        ]);
                        $service->save();
                    } else {
                        throw new AppException('Seçilen hizmette yeterli seansı bulunmuyor.'.$service->service()->name);
                        break;
                    }
                }
            } else {
                throw new AppException('Randevu oluşturulamadı.');
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
