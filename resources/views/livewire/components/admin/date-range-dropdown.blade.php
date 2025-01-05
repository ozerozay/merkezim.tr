<?php


use Carbon\Carbon;

new class extends \Livewire\Volt\Component {
    #[\Livewire\Attributes\Modelable]
    public $selectedDateRange;

    public function updatedSelectedDateRange($value)
    {
        $dateRange = $this->getDateRange($value);
        $this->dispatch('dateRangeUpdated', $dateRange);
    }

    public function getDateRange($range)
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        switch ($range) {
            case 'today':
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'yesterday':
                $start = Carbon::now()->subDay()->startOfDay();
                $end = Carbon::now()->subDay()->endOfDay();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'last_month':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'last_3_months':
                $start = Carbon::now()->subMonths(3)->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
        }

        return ['start' => $start->toDateString(), 'end' => $end->toDateString()];
    }
};
?>
<div wire:key="cxvkjn-{{Str::random(10)}}">
    <select wire:model.live="selectedDateRange"
            wire:key="cxvkdddffjn-{{Str::random(10)}}"
            class="bg-base-200 text-base-content text-sm px-3 py-2 rounded-lg border border-base-300 focus:outline-none">
        <option value="today">Today</option>
        <option value="yesterday">Yesterday</option>
        <option value="this_week">This Week</option>
        <option value="this_month">This Month</option>
        <option value="last_month">Last Month</option>
        <option value="last_3_months">Last 3 Months</option>
    </select>
</div>
