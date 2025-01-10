<?php

namespace App\Livewire;

use Livewire\Component;
use App\Actions\Spotlight\Actions\Kasa\GetKasaTransactions;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;

#[Lazy]
class KasaHareketleriTable extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="animate-pulse">
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4 mb-4"></div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                    <div class="space-x-3 flex items-center">
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                    <div class="space-x-3 flex items-center">
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
                    <div class="space-x-3 flex items-center">
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function getKasaTransactions()
    {
        $transactions = GetKasaTransactions::run([
            'branches' => auth()->user()->staff_branches,
            'first_date' => date('Y-m-d'),
            'last_date' => date('Y-m-d'),
        ])->flatten(1)->toArray();

        $groupedTransactions = [];
        foreach ($transactions as $transaction) {
            $branchName = $transaction['branch']['name'] ?? $transaction['branch_name'];
            $bakiye = $transaction['devir'] + $transaction['odenen'] + $transaction['tahsilat'];

            if (!isset($groupedTransactions[$branchName])) {
                $groupedTransactions[$branchName] = [
                    'branch_name' => $branchName,
                    'transactions' => [],
                    'totals' => [
                        'devir' => 0,
                        'tahsilat' => 0,
                        'odenen' => 0,
                        'bakiye' => 0
                    ]
                ];
            }

            $groupedTransactions[$branchName]['transactions'][] = [
                'id' => $transaction['id'],
                'label' => $transaction['name'] ?? $transaction['kasa_adi'],
                'kasa_adi' => $transaction['name'] ?? $transaction['kasa_adi'],
                'devir' => $transaction['devir'],
                'tahsilat' => $transaction['tahsilat'],
                'odenen' => $transaction['odenen'],
                'bakiye' => $bakiye,
                'group' => $branchName
            ];

            $groupedTransactions[$branchName]['totals']['devir'] += $transaction['devir'];
            $groupedTransactions[$branchName]['totals']['tahsilat'] += $transaction['tahsilat'];
            $groupedTransactions[$branchName]['totals']['odenen'] += $transaction['odenen'];
            $groupedTransactions[$branchName]['totals']['bakiye'] += $bakiye;
        }

        return $groupedTransactions;
    }

    public function showKasaDetail($kasaId)
    {
        $this->dispatch(
            'slide-over.open',
            component: 'actions.kasa.kasa-processes',
            arguments: ['kasa' => $kasaId]
        );
    }

    public function render()
    {
        return view('livewire.kasa-hareketleri-table');
    }
}
