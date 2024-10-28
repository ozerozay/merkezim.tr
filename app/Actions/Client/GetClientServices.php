<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\ClientService;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientServices
{
    use AsAction;

    public function handle($client, $paginate, $order)
    {
        try {

            $query = ClientService::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->with('service:id,name', 'sale:id,unique_id', 'package:id,name', 'userServices:id,name', 'clientServiceUses.user:id,name');

            return $paginate ? $query->paginate(10) : $query->get();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
