<?php

namespace App\Actions\Spotlight\Queries;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class CouponQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('coupon', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $couponToken, $query) {

            $results = collect();

            $results->push(
                SpotlightResult::make()
                    ->setTitle('Geri Dön')
                    ->setGroup('backk')
                    ->setIcon('arrow-left')
                    ->setAction('return_action'),
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Tümünü görüntüle')
                ->setGroup('actions')
                ->setIcon('check-circle')
                ->setAction('jump_to',
                    ['path' => route('admin.client.profil.index', ['user' => $clientToken->getParameter('id'), 'tab' => 'coupon']),
                    ]));

            return $results;
        });
    }
}
