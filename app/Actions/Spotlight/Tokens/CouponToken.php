<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Coupon;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class CouponToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('coupon', function (SpotlightScopeToken $token, Coupon $coupon) {
            $coupon->refresh();
            $token->setParameters(['client' => $coupon->client_id]);
            $token->setParameters(['id' => $coupon->id]);
            $token->setText('Kuponlar');
        });
    }
}
