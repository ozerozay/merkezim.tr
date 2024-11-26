<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\ClientService;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class ClientServiceToken
{
    use AsAction;

    public function handle(): SpotlightScopeToken
    {
        return SpotlightScopeToken::make('clientService', function (SpotlightScopeToken $token, ClientService $clientService) {
            $clientService->refresh();
            $token->setParameters(['client' => $clientService->client_id]);
            $token->setParameters(['id' => $clientService->id]);
            $token->setText('Hizmetler');
        });
    }
}
