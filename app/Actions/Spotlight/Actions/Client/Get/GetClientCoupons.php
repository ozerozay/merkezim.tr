<?php

namespace App\Actions\Spotlight\Actions\Client\Get;

use App\Models\Coupon;
use Lorisleiva\Actions\Concerns\AsAction;

class GetClientCoupons
{
    use AsAction;

    public function handle($client, $minOrder = 0)
    {
        return Coupon::query()
            ->where('client_id', $client)
            ->where('count', '>', 0)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '<', now());
            })
            ->when($minOrder > 0, function ($q) use ($minOrder) {
                $q->where('min_order', '>=', $minOrder);
            })
            ->get();
    }
}
