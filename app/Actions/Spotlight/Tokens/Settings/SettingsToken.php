<?php

namespace App\Actions\Spotlight\Tokens\Settings;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class SettingsToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('settings', function (SpotlightScopeToken $token, User $settings): void {
            $settings->refresh();
            $token->setText('Ayarlar');
        });
    }
}
