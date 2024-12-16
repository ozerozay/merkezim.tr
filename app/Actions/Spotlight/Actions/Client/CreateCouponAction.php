<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Models\Coupon;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateCouponAction
{
    use AsAction;

    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $id = Coupon::create($info);

            \DB::commit();

            return [$id->id];
        }, $approve);
    }
}
