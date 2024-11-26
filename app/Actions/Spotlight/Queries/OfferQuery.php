<?php

namespace App\Actions\Spotlight\Queries;

use App\Models\Offer;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class OfferQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('offer', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $offerToken, $query) {
            $offerList = Offer::query()
                ->select('id', 'client_id', 'created_at', 'status')
                ->where('client_id', '=', $clientToken->getParameter('id'))
                //->with('sale:id,sale_no,date', 'service:id,name')
                ->get();

            $offerGroup = $offerList->groupBy('status');
            $results = collect();

            foreach ($offerGroup as $status => $offers) {
                foreach ($offers as $offer) {
                    $results->push(SpotlightResult::make()
                        ->setTitle("{$offer->created_at}")
                        //->setSubtitle('Satış: '.$service->sale?->sale_no.' | Toplam: '.$service->total.' | Kalan: '.$service->remaining)
                        ->setGroup($offer->status->name)
                        ->setIcon('check-circle')
                    );
                }
            }

            return $results;
        });
    }
}
