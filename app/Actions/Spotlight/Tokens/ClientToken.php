<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class ClientToken
{
    use AsAction;

    public function handle(): SpotlightScopeToken
    {
        return SpotlightScopeToken::make('client', function (SpotlightScopeToken $token, User $client): void {
            $client->refresh();
            $token->setParameters(['id' => $client->id]);
            $token->setText($client->name.' - '.$client->client_branch->name);
        });
    }
}
