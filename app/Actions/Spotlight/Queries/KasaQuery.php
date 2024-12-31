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

            if (SpotlightCheckPermission::run(PermissionType::kasa_make_payment)) {
                $results->push(SpotlightResult::make()
                    ->setTitle('Ödeme Yap')
                    ->setGroup('actions')
                    ->setSubtitle('Danışan, müşteri veya masraf gruplarına ödeme yapın.')
                    ->setIcon('arrow-right')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.kasa.create-payment'],
                        ]));
            }
            if (SpotlightCheckPermission::run(PermissionType::kasa_make_payment)) {
                $results->push(SpotlightResult::make()
                    ->setTitle('Ödeme Al')
                    ->setGroup('actions')
                    ->setSubtitle('Tahsilat için danışanın menüsüne girin.')
                    ->setIcon('arrow-right')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.kasa.create-paid'],
                        ]));
            }

            if (SpotlightCheckPermission::run(PermissionType::kasa_mahsup)) {
                $results->push(SpotlightResult::make()
                    ->setTitle('Mahsup')
                    ->setGroup('actions')
                    ->setSubtitle('İşlemler')
                    ->setSubtitle('Kasalar arası para transferi')
                    ->setIcon('arrow-right')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.kasa.create-mahsup'],
                        ]));
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
                    ->setTitle($transaction['kasa_adi'].' - '.'Devir: '.$transaction['devir'].' | Tahsilat: '.$transaction['tahsilat'].' | Ödenen: '.$transaction['odenen'].' | Bakiye: '.$transaction['bakiye'])
                    ->setGroup($transaction['branch_name'])
                    ->setIcon('check-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.kasa.kasa-processes', 'arguments' => ['kasa' => $transaction['id']]],
                        ]));
            }

            $results->push(
                SpotlightResult::make()
                    ->setTitle('Geri Dön')
                    ->setGroup('backk')
                    ->setIcon('arrow-left')
                    ->setAction('return_action'),
            );

            return $results;
        });
    }
}
