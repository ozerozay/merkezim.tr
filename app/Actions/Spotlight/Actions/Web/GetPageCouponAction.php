<?php

namespace App\Actions\Spotlight\Actions\Web;

use App\Models\Coupon;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetPageCouponAction
{
    use AsAction;

    public function handle()
    {
        try {
            return Coupon::query()
                ->where('client_id', auth()->user()->id)
                ->where('count', '>', 0)
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>', now());
                })
                ->latest()
                ->get();
        } catch (\Throwable $e) {
            throw ToastException::error('Lütfen tekrar deneyin.');
        }
    }
}
