<?php

namespace App\Actions\Spotlight\Actions\Get;

use App\Models\Sale;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSaleNoAction
{
    use AsAction;

    public function handle($branch)
    {
        $last_sale = Sale::select('id', 'sale_no')->where('branch_id', $branch)->latest()->first();
        if ($last_sale) {
            return $last_sale->sale_no + 1;
        }

        return 1;
    }
}
