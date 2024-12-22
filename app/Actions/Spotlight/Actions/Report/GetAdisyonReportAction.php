<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\Adisyon;
use App\Peren;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAdisyonReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return Adisyon::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = Peren::formatRangeDate($filters['date_range']);
                    $q->whereDate('date', '>=', $date['first_date'])->whereDate('date', '<=', $date['last_date']);
                })
                ->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('client.client_branch', function ($qa) use ($filters) {
                        $qa->whereIn('id', $filters['branches']);
                    });
                })->when(array_key_exists('staffs', $filters) && ! $filters['staffs'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('user_id', $filters['staffs']);
                })->when(array_key_exists('client', $filters) && ! $filters['client'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('client_id', $filters['client']);
                })
                ->orderBy(...array_values($sortBy))
                ->with('client:id,name,branch_id', 'staffs:id,name', 'branch:id,name')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
