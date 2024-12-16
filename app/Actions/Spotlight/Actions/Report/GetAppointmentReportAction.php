<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\Appointment;
use App\Peren;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAppointmentReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {
            return Appointment::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = Peren::formatRangeDate($filters['date_range']);
                    $q->whereDate('date', '>=', $date['first_date'])->whereDate('date', '<=', $date['last_date']);
                })
                ->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('branch_id', $filters['branches']);
                })
                ->when(array_key_exists('select_status_id', $filters) && ! $filters['select_status_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('status', $filters['select_status_id']);
                })
                ->when(array_key_exists('select_create_staff_id', $filters) && ! $filters['select_create_staff_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('user_id', $filters['select_create_staff_id']);
                })
                ->when(array_key_exists('select_finish_staff_id', $filters) && ! $filters['select_finish_staff_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('finish_user_id', $filters['select_finish_staff_id']);
                })
                ->orderBy(...array_values($sortBy))
                ->with('branch:id,name', 'serviceRoom:id,name', 'serviceCategory:id,name', 'client:id,name', 'finish_user:id,name', 'finish_services.service:id,name')
                ->paginate(10);
        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }

    }
}
