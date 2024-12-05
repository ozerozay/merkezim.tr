<?php

namespace App\Actions\Spotlight\Tokens\Pages;

use App\Models\Branch;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class AppointmentPageBranchToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('pab', function (SpotlightScopeToken $token, Branch $pab) {
            $pab->refresh();

            $token->setParameters(['id' => $pab->id]);
            $token->setText($pab->name);
        });
    }
}
