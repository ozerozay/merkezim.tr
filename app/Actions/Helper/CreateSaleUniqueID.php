<?php

namespace App\Actions\Helper;

use App\Models\Sale;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSaleUniqueID
{
    use AsAction;

    public function handle()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (Sale::select('unique_id')->where('unique_id', '=', $code)->exists());

        return $code;
    }
}
