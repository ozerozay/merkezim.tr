<?php

namespace App\Actions\Client;

use App\Models\ClientService;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientActiveService
{
    use AsAction;

    public function handle($client, $status, $category)
    {
        return ClientService::query()
            ->select(['id', 'service_id', 'client_id', 'sale_id', 'status', 'total', 'remaining', 'sale_id'])
            ->where('client_id', $client)
            ->where('status', $status ?? SaleStatus::success)
            ->where('remaining', '>', 0)
            ->when($category, function ($query) use ($category) {
                $query->whereHas('service', function ($q) use ($category) {
                    $q->where('category_id', $category);
                });
            })
            ->with(['service:id,name,duration', 'sale:id,sale_no'])
            ->get();
    }
}
