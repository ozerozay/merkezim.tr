<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\Talep;
use App\Peren;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTalepReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return Talep::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = Peren::formatRangeDate($filters['date_range']);
                    $q->whereDate('date', '>=', $date['first_date'])->whereDate('date', '<=', $date['last_date']);
                })->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('branch_id', $filters['branches']);
                })
                ->when(array_key_exists('staffs', $filters) && ! $filters['staffs'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('user_id', $filters['staffs']);
                })
                ->when(array_key_exists('types', $filters) && ! $filters['types'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('type', $filters['types']);
                })->when(array_key_exists('statuses', $filters) && ! $filters['statuses'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('status', $filters['statuses']);
                })
                ->orderBy(...array_values($sortBy))
                ->with('branch:id,name', 'user:id,name', 'talepProcesses')
                ->withCount('talepProcesses')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
