<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Adisyon;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class AdisyonToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('adisyon', function (SpotlightScopeToken $token, Adisyon $adisyon) {
            $adisyon->refresh();
            $token->setParameters(['client' => $adisyon->client_id]);
            $token->setParameters(['id' => $adisyon->id]);
            $token->setText('Adisyon');
        });
    }
}
