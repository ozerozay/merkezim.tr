<?php

namespace App\Actions\Spotlight\Actions\Check;

use App\AppointmentStatus;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckAppointmentTimeAvaible
{
    use AsAction;

    public function handle($info): bool
    {
        try {
            return Appointment::query()
                ->where('service_room_id', $info['service_room_id'])
                ->where('date', $info['start_date']->format('Y-m-d'))
                ->whereNotIn('status',
                    [AppointmentStatus::rejected, AppointmentStatus::cancel]
                )->where(function ($q) use ($info) {
                    $q->where(function ($sub) use ($info) {
                        $sub->where('date_start', '<=', $info['start_date']->toDateTimeString())
                            ->where('date_end', '>=', $info['end_date']->toDateTimeString());
                    })
                        ->orWhere(function ($q) use ($info) {
                            $q->whereBetween('date_start', [$info['start_date']->toDateTimeString(), $info['end_date']->toDateTimeString()]);
                        })
                        ->orWhere(function ($q) use ($info) {
                            $q->whereBetween('date_end', [$info['start_date']->toDateTimeString(), $info['end_date']->toDateTimeString()]);
                        });
                })->exists();

        } catch (\Throwable $e) {
            return true;
        }
    }
}
