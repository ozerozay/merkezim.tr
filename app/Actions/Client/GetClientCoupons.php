<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\Coupon;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientCoupons
{
    use AsAction;

    public function handle($client, $paginate, $order)
    {
        try {

            $query = Coupon::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->with('user:id,name');

            return $paginate ? $query->paginate($paginate) : $query->get();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
