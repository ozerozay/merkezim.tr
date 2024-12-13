<?php

namespace App\Livewire\Reports\Filter;

use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ServiceReportFilter extends SlideOver
{
    use Toast;

    public $branches = [];

    public $date_range;

    public $select_status_id = [];

    public $select_staff_id = [];

    public $select_service_id = [];

    public $select_remaining_id = false;

    public $select_gift_id = false;

    public function mount($filters)
    {
        $this->fill($filters);
    }

    public function save()
    {
        $filters = [
            'date_range' => $this->date_range,
            'select_status_id' => $this->select_status_id,
            'select_staff_id' => $this->select_staff_id,
            'select_service_id' => $this->select_service_id,
            'select_remaining_id' => $this->select_remaining_id,
            'select_gift_id' => $this->select_gift_id,
        ];

        $this->dispatch('report-filter', $filters);
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.reports.filter.service-report-filter');
    }
}
