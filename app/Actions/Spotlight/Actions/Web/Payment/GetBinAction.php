<?php

namespace App\Actions\Spotlight\Actions\Web\Payment;

use Lorisleiva\Actions\Concerns\AsAction;

class GetBinAction
{
    use AsAction;

    public function handle($cc, $branch): \Illuminate\Support\Collection
    {
        try {
            return collect((new \App\Managers\PayTRPaymentManager)->bin($cc, $branch));
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
