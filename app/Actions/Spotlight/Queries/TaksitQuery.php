<?php

namespace App\Actions\Spotlight\Queries;

use App\Models\ClientTaksit;
use App\Traits\LiveHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class TaksitQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('taksit', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $taksitToken, $query) {
            $taksitList = ClientTaksit::query()
                ->select('id', 'client_id', 'date', 'status', 'remaining', 'sale_id', 'total')
                ->where('client_id', '=', $clientToken->getParameter('id'))
                ->orderBy('date', 'asc')
                ->with('sale:id,sale_no,date')
                ->get();

            $taksitGroup = $taksitList->groupBy('status');
            $results = collect();

            $results->push(SpotlightResult::make()
                ->setTitle('Tümünü görüntüle')
                ->setGroup('actions')
                ->setIcon('check-circle')
                ->setAction('jump_to',
                    ['path' => route('admin.client.profil.index', ['user' => $clientToken->getParameter('id'), 'tab' => 'taksit']),
                    ]));

            foreach ($taksitGroup as $status => $taksits) {
                foreach ($taksits as $taksit) {
                    $results->push(SpotlightResult::make()
                        ->setTitle("{$taksit->dateHuman} - ".($taksit->sale->sale_no ?? ''))
                        ->setSubtitle('Kalan: '.LiveHelper::price_text($taksit->remaining).' | Toplam: '.LiveHelper::price_text($taksit->total))
                        ->setGroup(($taksit->remaining == 0 ? 'finish' : $taksit->status->name))
                        ->setIcon('check-circle')
                    );
                }
            }

            return $results;
        });
    }
}
