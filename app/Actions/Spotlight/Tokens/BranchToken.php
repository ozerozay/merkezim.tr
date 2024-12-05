<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Branch;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class BranchToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('branch', function (SpotlightScopeToken $token, Branch $branch) {
            $token->setText('Şube');
            $token->setParameters(['branch_id' => $branch->id]);
        });
    }
}
