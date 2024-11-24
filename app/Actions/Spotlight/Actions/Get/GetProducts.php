<?php

namespace App\Actions\Spotlight\Actions\Get;

use App\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class GetProducts
{
    use AsAction;

    public function handle($branch_ids, $in_stock = true)
    {
        return Product::query()
            ->where('active', true)
            ->when($branch_ids, function ($q) use ($branch_ids) {
                $q->whereIn('branch_id', $branch_ids);
            })
            ->when($in_stock, function ($q) {
                $q->where('stok', '>', 0);
            })
            ->orderBy('name')
            ->get();
    }
}
