<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Sale;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class SaleToken
{
    use AsAction;

    public function handle(): SpotlightScopeToken
    {
        return SpotlightScopeToken::make('sale', function (SpotlightScopeToken $token, Sale $sale) {
            $sale->refresh();
            $token->setParameters(['client' => $sale->client_id]);
            $token->setParameters(['id' => $sale->id]);
            $token->setText('Satışlar');
        });
    }
}
