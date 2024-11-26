<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\SPotlight\Actions\Kasa\GetKasaTransactions;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\Spotlight;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class KasaQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('kasa', function (SpotlightScopeToken $kasaToken, $query) {
            $results = collect();

            foreach (auth()->user()->staff_branch as $branch) {
                Spotlight::registerGroup($branch->name, $branch->name);
            }

            $transactions = GetKasaTransactions::run([
                'branches' => auth()->user()->staff_branches,
                'first_date' => date('Y-m-d'),
                'last_date' => date('Y-m-d'),
            ])->flatten(1)->toArray();

            $collected_transactions = collect($transactions);

            foreach ($transactions as $transaction) {
                $transaction['bakiye'] = $transaction['devir'] + $transaction['odenen'] + $transaction['tahsilat'];
                $results->push(SpotlightResult::make()
                    ->setTitle($transaction['kasa_adi'])
                    ->setSubtitle('Devir: '.$transaction['devir'].' | Tahsilat: '.$transaction['tahsilat'].' | Ödenen: '.$transaction['odenen'].' | Bakiye: '.$transaction['bakiye'])
                    ->setGroup($transaction['branch_name'])
                    ->setIcon('check-circle')
                );
            }

            return $results;
        });
    }
}
