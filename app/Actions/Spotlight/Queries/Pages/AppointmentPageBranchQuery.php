<?php

namespace App\Actions\Spotlight\Queries\Pages;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class AppointmentPageBranchQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('pab', function ($query, SpotlightScopeToken $pabToken) {
            $results = collect();

            return $results;
        });
    }
}
