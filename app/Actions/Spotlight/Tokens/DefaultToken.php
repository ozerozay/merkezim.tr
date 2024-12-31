<?php

namespace App\Actions\Spotlight\Tokens;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class DefaultToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('default', function (SpotlightScopeToken $token): void {});
    }
}
