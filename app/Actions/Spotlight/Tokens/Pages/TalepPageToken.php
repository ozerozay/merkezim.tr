<?php

namespace App\Actions\Spotlight\Tokens\Pages;

use App\Models\Talep;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class TalepPageToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('page_talep', function (SpotlightScopeToken $token, Talep $appointment) {
            $appointment->refresh();
            $token->setText('Talepler');
        });
    }
}
