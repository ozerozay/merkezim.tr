<?php

namespace App\Actions\Spotlight\Tokens;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class ReportToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('reports', function (SpotlightScopeToken $token, \App\Models\User $reports): void {
            $reports->refresh();
            $token->setText('Raporlar');
        });
    }
}
