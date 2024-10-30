<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\SaleProduct;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientProductSales
{
    use AsAction;

    public function handle($client, $paginate, $order)
    {
        try {
            $query = SaleProduct::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->withCount(['saleProductItems']);

            return $paginate ? $query->paginate(10) : $query->get();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
