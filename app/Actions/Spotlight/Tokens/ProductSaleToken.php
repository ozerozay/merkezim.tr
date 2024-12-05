<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\SaleProduct;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class ProductSaleToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('product', function (SpotlightScopeToken $token, SaleProduct $saleProduct) {
            $saleProduct->refresh();
            $token->setParameters(['client' => $saleProduct->client_id]);
            $token->setParameters(['id' => $saleProduct->id]);
            $token->setText('Ürün Satışları');
        });
    }
}
