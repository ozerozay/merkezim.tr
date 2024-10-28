<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\Sale;
use App\SaleStatus;
use App\TransactionType;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientSales
{
    use AsAction;

    public function handle($client, $paginate, $order)
    {
        try {

            $query = Sale::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->with('staffs:id,name')
                ->withSum(['clientTaksits as total_taksit_remaining' => function ($q) {
                    $q->where('status', SaleStatus::success);
                }], 'remaining')
                ->withSum(['clientTaksits as total_taksit' => function ($q) {
                    $q->where('status', SaleStatus::success);
                }], 'total')
                ->withSum(['clientServices as total_service_remaining' => function ($q) {
                    $q->where('status', SaleStatus::success);
                }], 'remaining')
                ->withSum(['clientServices as total_service' => function ($q) {
                    $q->where('status', SaleStatus::success);
                }], 'total')
                ->withSum(['clientTaksits as total_late_payment' => function ($q) {
                    $q->where('status', SaleStatus::success)
                        ->where('date', '<', date('Y-m-d'));
                }], 'total')
                ->withSum(['transactions as pesinat' => function ($q) {
                    $q->where('type', TransactionType::pesinat);
                }], 'price');

            return $paginate ? $query->paginate(10) : $query->get();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
