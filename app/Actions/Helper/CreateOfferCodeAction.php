<?php

namespace App\Actions\Helper;

use App\Models\Offer;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateOfferCodeAction
{
    use AsAction;

    public function handle()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Offer::select('unique_id')->where('unique_id', '=', $code)->exists());

        return $code;
    }
}
