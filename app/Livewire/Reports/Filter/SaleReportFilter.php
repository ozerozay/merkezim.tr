<?php

namespace App\Livewire\Reports\Filter;

use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleReportFilter extends SlideOver
{
    use Toast;

    public $date_range = null;

    public $select_type_id = [];

    public $select_status_id = [];

    public $select_staff_id = [];

    public $branches = [];

    public function mount($filters)
    {
        $this->fill($filters);
    }

    public function save()
    {
        $filters = [
            'date_range' => $this->date_range,
            'select_type_id' => $this->select_type_id,
            'select_status_id' => $this->select_status_id,
            'select_staff_id' => $this->select_staff_id,
            'branches' => $this->branches,
        ];

        $this->dispatch('report-sale-filter', $filters);
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.reports.filter.sale-report-filter');
    }
}
