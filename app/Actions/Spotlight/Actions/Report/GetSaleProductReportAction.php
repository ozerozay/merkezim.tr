<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\SaleProduct;
use App\Peren;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSaleProductReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return SaleProduct::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = Peren::formatRangeDate($filters['date_range']);
                    $q->whereDate('date', '>=', $date['first_date'])->whereDate('date', '<=', $date['last_date']);
                })->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('branch_id', $filters['branches']);
                })
                ->when(array_key_exists('$staff_create', $filters) && ! $filters['$staff_create'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('user_id', $filters['$staff_create']);
                })
                ->when(array_key_exists('$client', $filters) && ! $filters['$client'] == null, function (Builder $q) use ($filters) {
                    $q->where('client_id', $filters['$client']);
                })
                ->when(array_key_exists('products', $filters) && ! $filters['products'] == null, function (Builder $q) use ($filters) {
                    $q->whereHas('saleProductItems', function (Builder $qa) use ($filters) {
                        $qa->whereIn('product_id', $filters['products']);
                    });
                })
                ->orderBy(...array_values($sortBy))
                ->with('branch:id,name', 'client:id,name', 'user:id,name', 'saleProductItems.product')
                ->withCount('saleProductItems')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);

        }
    }
}
