<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\Branch;
use App\Traits\AdminWidgetTrait;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy()]
class LastTransactionsWidget extends Component
{
    use AdminWidgetTrait;

    public $branchesData = [];
    public $selectedDateRange = 'today';

    public function loadData(): void
    {
        $dateRange = $this->getDateRange();

        $this->branchesData = Branch::whereIn('id', auth()->user()->staff_branches)
            ->where('active', true)
            ->whereIn('id', $this->selectedBranches)
            ->withSum(['transactions as tahsilat' => function ($query) use ($dateRange) {
                $query->where('date', '>=', $dateRange['start'])
                    ->where('date', '<=', $dateRange['end'])
                    ->where('price', '>', 0);
            }], 'price')
            ->withSum(['transactions as odeme' => function ($query) use ($dateRange) {
                $query->where('date', '>=', $dateRange['start'])
                    ->where('date', '<=', $dateRange['end'])
                    ->where('price', '<', 0);
            }], 'price')
            ->get()
            ->map(function ($branch) {
                $tahsilat = $branch->tahsilat ?? 0;
                $odeme = $branch->odeme ?? 0;

                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'tahsilat' => $tahsilat,
                    'odeme' => $odeme,
                    'bakiye' => $tahsilat - $odeme
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.spotlight.admin.widgets.last-transactions-widget');
    }
}
