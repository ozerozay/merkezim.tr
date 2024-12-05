<?php

namespace App\Actions\Spotlight\Queries;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class AdisyonQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('adisyon', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $adisyonToken, $query) {

            $results = collect();

            $results->push(SpotlightResult::make()
                ->setTitle('Tümünü görüntüle')
                ->setGroup('actions')
                ->setIcon('check-circle')
                ->setAction('jump_to',
                    ['path' => route('admin.client.profil.index', ['user' => $clientToken->getParameter('id'), 'tab' => 'adisyon']),
                    ]));

            return $results;
        });
    }
}
