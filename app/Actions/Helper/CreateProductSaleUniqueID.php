<?php

namespace App\Actions\Helper;

use App\Models\SaleProduct;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateProductSaleUniqueID
{
    use AsAction;

    public function handle()
    {
        do {
            $code = random_int(100000000, 999999999);
        } while (SaleProduct::select('unique_id')->where('unique_id', '=', $code)->exists());

        return $code;
    }
}
