<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\Actions\Kasa\GetKasaTransactions;
use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
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

            if (SpotlightCheckPermission::run(PermissionType::client_profil_sale)) {
                $results->push(SpotlightResult::make()
                    ->setTitle('Ödeme Yap')
                    ->setGroup('actions')
                    ->setSubtitle('Danışan, müşteri veya masraf gruplarına ödeme yapın.')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    //->setTokens(['client' => User::find($clientToken->getParameter('id')), 'sale' => new Sale])
                    ->setIcon('arrow-right'));
                $results->push(SpotlightResult::make()
                    ->setTitle('Ödeme Al')
                    ->setGroup('actions')
                    ->setSubtitle('Tahsilat için danışanın menüsüne girin.')
                //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                //->setTokens(['client' => User::find($clientToken->getParameter('id')), 'sale' => new Sale])
                    ->setIcon('arrow-right'));
            }

            if (SpotlightCheckPermission::run(PermissionType::client_profil_sale)) {
                $results->push(SpotlightResult::make()
                    ->setTitle('Mahsup')
                    ->setGroup('actions')
                    ->setSubtitle('İşlemler')
                    ->setSubtitle('Kasalar arası para transferi')
                    //->setAction('jump_to', ['path' => route('admin.client.profil.index', 1)])
                    //->setTokens(['client' => User::find($clientToken->getParameter('id')), 'sale' => new Sale])
                    ->setIcon('arrow-right'));
            }

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
