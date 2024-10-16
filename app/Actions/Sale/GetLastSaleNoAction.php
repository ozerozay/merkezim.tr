<?php

namespace App\Actions\Sale;

use App\Models\Sale;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLastSaleNoAction
{
    use AsAction;

    public function handle($branch)
    {
        $last_sale = Sale::where('branch_id', $branch)->whereIn('status', [SaleStatus::success, SaleStatus::waiting])->latest()->first();
        if ($last_sale) {
            return $last_sale->sale_no + 1;
        }

        return 1;
    }
}
