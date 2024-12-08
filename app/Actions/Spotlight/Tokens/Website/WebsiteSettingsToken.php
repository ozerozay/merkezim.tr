<?php

namespace App\Actions\Spotlight\Tokens\Website;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class WebsiteSettingsToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('websitesettings', function (SpotlightScopeToken $token, User $websitesettings): void {
            $websitesettings->refresh();
            $token->setText('Site Ayarları');
        });
    }
}
