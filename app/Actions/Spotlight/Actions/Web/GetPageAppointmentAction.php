<?php

namespace App\Actions\Spotlight\Actions\Web;

use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetPageAppointmentAction
{
    use AsAction;

    public function handle($statuses)
    {
        try {

            return Appointment::query()
                ->select('id', 'status', 'date', 'client_id', 'date_start', 'date_end', 'service_ids', 'finish_service_ids')
                ->where('client_id', auth()->user()->id)
                ->whereIn('status', $statuses)
                ->with('services:id,service_id', 'services.service:id,name,is_visible,active', 'finish_services:id,service_id', 'finish_services.service:id,name,is_visible,active')
                ->orderBy('date', 'desc')
                ->get();

        } catch (\Throwable $e) {
            throw ToastException::error('Lütfen tekrar deneyin.'.$e->getMessage());
        }
    }
}
