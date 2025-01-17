<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\ClientTaksit;
use App\Peren;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTaksitReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return ClientTaksit::query()
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
                ->when(array_key_exists('select_remaining_id', $filters) && ! $filters['select_remaining_id'] == null, function (Builder $q) use ($filters) {
                    if ($filters['select_remaining_id']) {
                        $q->where('remaining', '>', 0);
                    }
                })->when(array_key_exists('select_lock_id', $filters) && ! $filters['select_lock_id'] == null, function (Builder $q) use ($filters) {
                    if ($filters['select_lock_id']) {
                        $q->whereHas('clientTaksitsLocks');
                    }
                })
                ->orderBy(...array_values($sortBy))
                ->with('branch:id,name', 'client:id,name', 'clientTaksitsLocks.service:id,name')
                ->withCount('clientTaksitsLocks')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
