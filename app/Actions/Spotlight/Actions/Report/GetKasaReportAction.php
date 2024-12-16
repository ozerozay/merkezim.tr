<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\Transaction;
use App\Peren;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;

class GetKasaReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return Transaction::query()
                ->when(array_key_exists('date_range', $filters) && ! $filters['date_range'] == null, function (Builder $q) use ($filters) {
                    $date = Peren::formatRangeDate($filters['date_range']);
                    $q->whereDate('date', '>=', $date['first_date'])->whereDate('date', '<=', $date['last_date']);
                })
                ->when(array_key_exists('branches', $filters) && ! $filters['branches'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('branch_id', $filters['branches']);
                })
                ->when(array_key_exists('select_type_id', $filters) && ! $filters['select_type_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('type', $filters['select_type_id']);
                })
                ->when(array_key_exists('select_kasa_id', $filters) && ! $filters['select_kasa_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('kasa_id', $filters['select_kasa_id']);
                })
                ->when(array_key_exists('select_masraf_id', $filters) && ! $filters['select_masraf_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('masraf_id', $filters['select_masraf_id']);
                })
                ->when(array_key_exists('select_client_id', $filters) && ! $filters['select_client_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('client_id', $filters['select_client_id']);
                })
                ->when(array_key_exists('select_create_staff_id', $filters) && ! $filters['select_create_staff_id'] == null, function (Builder $q) use ($filters) {
                    $q->whereIn('user_id', $filters['select_create_staff_id']);
                })
                ->when(array_key_exists('select_payment_id', $filters) && ! $filters['select_payment_id'] == null, function (Builder $q) use ($filters) {
                    switch ($filters['select_payment_id']) {
                        case 1:
                            $q->where('price', '>', 0);
                            break;
                        default:
                            $q->where('price', '<', 0);
                            break;
                    }
                })
                ->orderBy(...array_values($sortBy))
                ->with('branch:id,name', 'kasa:id,name', 'masraf:id,name', 'client:id,name', 'user:id,name')
                ->paginate(10);

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);
        }
    }
}
