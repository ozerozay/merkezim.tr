<?php

namespace App\Livewire\Admin\Widgets;

use App\Models\Branch;
use App\Traits\AdminWidgetTrait;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy()]
class SaleWidget extends Component
{
    use AdminWidgetTrait;

    public $selectedBranchesData = [];
    public $selectedDateRange = 'today';

    public function loadData(): void
    {
        $dateRange = $this->getDateRange();

        $this->selectedBranchesData = Branch::whereIn('id', auth()->user()->staff_branches)
            ->where('active', true)
            ->whereIn('id', $this->selectedBranches)
            ->withCount([
                'sales as successful_sales_count' => function ($query) use ($dateRange) {
                    $query->where('date', '>=', $dateRange['start'])
                        ->where('date', '<=', $dateRange['end'])
                        ->where('status', 'success');
                }
            ])
            ->withSum(['sales' => function ($query) use ($dateRange) {
                $query->where('date', '>=', $dateRange['start'])
                    ->where('date', '<=', $dateRange['end'])
                    ->where('status', 'success');
            }], 'price')
            ->get()
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'successful_sales_count' => $branch->successful_sales_count ?? 0,
                    'total_sales' => $branch->sales_sum_price ?? 0,
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.spotlight.admin.widgets.sale-widget');
    }
}
