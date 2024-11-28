<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Models\Coupon;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateCouponAction
{
    use AsAction;

    public function handle($info): void
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            Coupon::create($info);
        });
    }
}
