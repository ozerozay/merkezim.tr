<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\ClientService;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetServiceReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {
            return ClientService::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = \App\Peren::formatRangeDate($filters['date_range']);
                    $q->whereRaw('DATE(created_at) >= ?', $date['first_date'])->whereRaw('DATE(created_at) <= ?', $date['last_date']);
                })
                ->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('branch_id', $filters['branches']);
                })
                ->when(! (array_key_exists('branches', $filters) && ! $filters['branches'] == null), function (Builder $q) {
                    $q->whereIn('branch_id', auth()->user()->staff_branches);
                })
                ->when(array_key_exists('select_status_id', $filters) && ! $filters['select_status_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('status', $filters['select_status_id']);
                })
                ->when(array_key_exists('select_staff_id', $filters) && ! $filters['select_staff_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('staffs', function ($qa) use ($filters) {
                        $qa->whereIn('id', $filters['select_type_id']);
                    });
                })
                ->when(array_key_exists('select_service_id', $filters) && ! $filters['select_service_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('service', function ($qa) use ($filters) {
                        $qa->whereIn('id', $filters['select_service_id']);
                    });
                })
                ->when(array_key_exists('select_remaining_id', $filters) && ! $filters['select_remaining_id'] == null, function (Builder $q) use ($filters) {
                    if ($filters['select_remaining_id']) {
                        $q->where('remaining', '>', 0);
                    }
                })
                ->when(array_key_exists('select_gift_id', $filters) && ! $filters['select_gift_id'] == null, function (Builder $q) use ($filters) {
                    if ($filters['select_gift_id']) {
                        $q->where('gift', true);
                    }
                })
                ->orderBy(...array_values($sortBy))
                ->with('client:id,name', 'sale:id,sale_no')
                ->paginate(10);
        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }

    }
}
