<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetOfferReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return Offer::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = \App\Peren::formatRangeDate($filters['date_range']);
                    $q->whereRaw('DATE(created_at) >= ?', $date['first_date'])->whereRaw('DATE(created_at) <= ?', $date['last_date']);
                })->when(array_key_exists('date_range_expire', $filters) && ! $filters['date_range_expire'] == null, function (Builder $q) use ($filters) {
                    $date = \App\Peren::formatRangeDate($filters['date_range_expire']);
                    $q->where('DATE(expire_date) >= ?', $date['first_date'])->whereRaw('DATE(expire_date) <= ?', $date['last_date']);
                })
                ->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('client.client_branch', function ($qa) use ($filters) {
                        $qa->whereIn('id', $filters['branches']);
                    });
                })->when(array_key_exists('staffs', $filters) && ! $filters['staffs'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('user_id', $filters['staffs']);
                })->when(array_key_exists('statuses', $filters) && ! $filters['statuses'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('status', $filters['statuses']);
                })
                ->orderBy(...array_values($sortBy))
                ->with('client:id,name,branch_id', 'user:id,name', 'client.client_branch:id,name')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
