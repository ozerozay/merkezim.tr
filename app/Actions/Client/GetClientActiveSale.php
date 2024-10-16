<?php

namespace App\Actions\Client;

use App\Models\Sale;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientActiveSale
{
    use AsAction;

    public function handle($client, $status)
    {
        return Sale::query()
            ->select(['id', 'unique_id', 'client_id', 'status', 'date'])
            ->where('client_id', $client)
            ->where('status', $status ?? SaleStatus::success)
            ->get();
    }
}
