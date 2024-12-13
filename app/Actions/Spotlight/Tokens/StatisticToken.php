<?php

namespace App\Actions\Spotlight\Tokens;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class StatisticToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('statistics', function (SpotlightScopeToken $token, \App\Models\User $statistics): void {
            $statistics->refresh();
            $token->setText('İstatistikler');
        });
    }
}
