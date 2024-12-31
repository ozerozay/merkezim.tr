<?php

namespace App\Actions\Spotlight\Queries;

use App\Models\Sale;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class SaleQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('sale', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $saleToken, $query) {
            $saleList = Sale::query()
                ->select('id', 'client_id', 'date', 'sale_no', 'status')
                ->where('client_id', '=', $clientToken->getParameter('id'))
                //->with('sale:id,sale_no,date', 'service:id,name')
                ->get();

            $saleGroup = $saleList->groupBy('status');
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
                    ['path' => route('admin.client.profil.index', ['user' => $clientToken->getParameter('id'), 'tab' => 'sale']),
                    ]));

            foreach ($saleGroup as $status => $sales) {
                foreach ($sales as $sale) {
                    $results->push(SpotlightResult::make()
                        ->setTitle("{$sale->sale_no} - {$sale->date}")
                        //->setSubtitle('Satış: '.$service->sale?->sale_no.' | Toplam: '.$service->total.' | Kalan: '.$service->remaining)
                        ->setGroup($sale->status->name)
                        ->setIcon('check-circle')
                    );
                }
            }

            return $results;
        });
    }
}
