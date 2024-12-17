<?php

namespace App\Livewire\Reports\Filter;

use App\Traits\ReportFilterHandler;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ApproveReportFilter extends SlideOver
{
    use ReportFilterHandler;

    public $date_range;

    public $branches = [];

    public $staffs_create = [];

    public $staffs_approve = [];

    public $types = [];

    public $statuses = [];

    public function render()
    {
        return view('livewire.spotlight.reports.filter.approve-report-filter');
    }
}
