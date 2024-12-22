<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\Approve;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetApproveReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return Approve::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = \App\Peren::formatRangeDate($filters['date_range']);
                    $q->whereRaw('DATE(created_at) >= ?', $date['first_date'])->whereRaw('DATE(created_at) <= ?', $date['last_date']);
                })->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('user.staff_branch', function ($qa) use ($filters) {
                        $qa->whereIn('id', $filters['branches']);
                    });
                })->when(array_key_exists('staffs_create', $filters) && ! $filters['staffs_create'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('user_id', $filters['staffs_create']);
                })->when(array_key_exists('staffs_approve', $filters) && ! $filters['staffs_approve'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('approved_by', $filters['staffs_approve']);
                })->when(array_key_exists('types', $filters) && ! $filters['types'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('type', $filters['types']);
                })->when(array_key_exists('statuses', $filters) && ! $filters['statuses'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('type', $filters['statuses']);
                })
                ->orderBy(...array_values($sortBy))
                ->with('user:id,name,staff_branches', 'user.staff_branch:id,name', 'approved_by:id,name')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
