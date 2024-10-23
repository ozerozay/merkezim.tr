<?php

namespace App\Actions\Coupon;

use App\Exceptions\AppException;
use App\Models\Coupon;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateCouponAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            $coupon = Coupon::create($info);

            throw_if(! $coupon, new AppException('Kupon oluşturulamadı.'));

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
