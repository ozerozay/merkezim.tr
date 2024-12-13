<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\Sale;
use App\Peren;
use App\SaleStatus;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSaleReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return Sale::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = Peren::formatRangeDate($filters['date_range']);
                    $q->whereDate('date', '>=', $date['first_date'])->whereDate('date', '<=', $date['last_date']);
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
                ->when(array_key_exists('select_type_id', $filters) && ! $filters['select_type_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('sale_type_id', $filters['select_type_id']);
                })
                ->when(array_key_exists('select_staff_id', $filters) && ! $filters['select_staff_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('staffs', function ($qa) use ($filters) {
                        $qa->whereIn('id', $filters['select_type_id']);
                    });
                })
                ->orderBy(...array_values($sortBy))
                ->withSum([
                    'clientTaksits as taksit_late_remaining' => function ($q) {
                        $q->whereDate('date', '<=', date('Y-m-d'))
                            ->where('status', SaleStatus::success);
                    },
                ], 'remaining')
                ->withSum([
                    'clientTaksits as taksits_remaining' => function ($q) {
                        $q->where('status', SaleStatus::success);
                    },
                ], 'remaining')
                ->withSum('transactions as tahsilat', 'price')
                ->with('branch:id,name', 'saleType:id,name', 'staffs:id,name')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
