<?php

namespace App\Actions\Spotlight\Actions\Create;

use App\Models\Coupon;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateCouponCode
{
    use AsAction;

    public function handle(): int
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Coupon::select('code')->where('code', '=', $code)->exists());

        return $code;
    }
}
