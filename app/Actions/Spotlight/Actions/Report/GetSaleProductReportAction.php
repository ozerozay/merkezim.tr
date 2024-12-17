<?php

namespace App\Actions\Spotlight\Actions\Report;

use App\Models\SaleProduct;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSaleProductReportAction
{
    use AsAction;

    public function handle($filters, $sortBy)
    {
        try {

            return SaleProduct::query();

        } catch (\Throwable $e) {
            dump($e->getMessage());

            return collect([]);

        }
    }
}
