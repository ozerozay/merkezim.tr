<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Offer;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class OfferToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('offer', function (SpotlightScopeToken $token, Offer $offer) {
            $offer->refresh();
            $token->setParameters(['client' => $offer->client_id]);
            $token->setParameters(['id' => $offer->id]);
            $token->setText('Teklifler');
        });
    }
}
