<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\ClientTaksit;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientTaksits
{
    use AsAction;

    public function handle($client, $paginate, $order, $saleID = null)
    {
        try {

            $query = ClientTaksit::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->withCount('clientTaksitsLocks')
                ->with('sale:id,unique_id,sale_no');

            return $paginate ? $query->paginate(10) : $query->get();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
