<?php

namespace App\Livewire\Reports\Filter;

use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class TaksitReportFilter extends SlideOver
{
    use Toast;

    public $branches = [];

    public $date_range;

    public $select_status_id = [];

    public $select_remaining_id = null;

    public $select_lock_id = false;

    public $select_remaining = [
        [
            'id' => 1,
            'name' => 'Ödenmiş taksitler',
        ],
        [
            'id' => 2,
            'name' => 'Ödenmemiş taksitler',
        ],
    ];

    public function mount($filters)
    {
        $this->fill($filters);
    }

    public function save()
    {
        $filters = [
            'date_range' => $this->date_range,
            'select_status_id' => $this->select_status_id,
            'select_remaining_id' => $this->select_remaining_id,
            'select_lock-id' => $this->select_lock_id,
            'branches' => $this->branches,
        ];

        $this->dispatch('report-filter', $filters);
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.reports.filter.taksit-report-filter');
    }
}
