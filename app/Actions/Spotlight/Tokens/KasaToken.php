<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Kasa;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class KasaToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('kasa', function (SpotlightScopeToken $token, Kasa $kasa): void {
            $kasa->refresh();
            $token->setParameters(['id' => $kasa->id]);
            $token->setText('Kasa');
        });
    }
}
