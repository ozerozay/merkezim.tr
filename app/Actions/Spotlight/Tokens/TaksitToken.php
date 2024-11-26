<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\ClientTaksit;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class TaksitToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('taksit', function (SpotlightScopeToken $token, ClientTaksit $taksit) {
            $taksit->refresh();
            $token->setParameters(['client' => $taksit->client_id]);
            $token->setParameters(['id' => $taksit->id]);
            $token->setText('Taksitler');
        });
    }
}
