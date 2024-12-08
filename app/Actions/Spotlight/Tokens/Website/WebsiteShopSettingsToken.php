<?php

namespace App\Actions\Spotlight\Tokens\Website;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class WebsiteShopSettingsToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('websiteshopsettings', function (SpotlightScopeToken $token, User $websiteshopsettings): void {
            $websiteshopsettings->refresh();
            $token->setText('Mağaza Ayarları');
        });
    }
}
