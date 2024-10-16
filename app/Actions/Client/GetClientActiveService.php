<?php

namespace App\Actions\Client;

use App\Models\ClientService;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientActiveService
{
    use AsAction;

    public function handle($client, $status)
    {
        return ClientService::query()
            ->select(['id', 'service_id', 'client_id', 'sale_id', 'status', 'total', 'remaining', 'sale_id'])
            ->where('client_id', $client)
            ->where('status', $status ?? SaleStatus::success)
            ->where('remaining', '>', 0)
            ->with(['service:id,name', 'sale:id,sale_no'])
            ->get();
    }
}
