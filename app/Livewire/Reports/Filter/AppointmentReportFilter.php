<?php

namespace App\Livewire\Reports\Filter;

use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AppointmentReportFilter extends SlideOver
{
    use Toast;

    public $branches = [];

    public $date_range;

    public $select_status_id = [];

    public $select_create_staff_id = [];

    public $select_finish_staff_id = [];

    public function mount($filters)
    {
        $this->fill($filters);
    }

    public function save()
    {
        $filters = [
            'date_range' => $this->date_range,
            'select_status_id' => $this->select_status_id,
            'select_create_staff_id' => $this->select_create_staff_id,
            'select_finish_staff_id' => $this->select_finish_staff_id,
            'branches' => $this->branches,
        ];

        $this->dispatch('report-filter', $filters);
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.reports.filter.appointment-report-filter');
    }
}
