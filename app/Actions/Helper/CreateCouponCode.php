<?php

namespace App\Actions\Helper;

use App\Models\Coupon;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateCouponCode
{
    use AsAction;

    public function handle()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Coupon::select('code')->where('code', '=', $code)->exists());

        return $code;
    }
}
