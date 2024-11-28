<?php

namespace App\Actions\Spotlight\Actions\Product;

use App\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateProductStok
{
    use AsAction;

    public function handle($product_id, $amount): void
    {
        Product::where('id', $product_id)->decrement('stok', $amount);
    }
}
