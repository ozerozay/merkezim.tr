<?php

namespace App\Livewire\Reports\Filter;

use App\Traits\ReportFilterHandler;
use WireElements\Pro\Components\SlideOver\SlideOver;

class TalepReportFilter extends SlideOver
{
    use ReportFilterHandler;

    public $date_range;

    public $branches = [];

    public $statuses = [];

    public $staffs = [];

    public $types = [];

    public function render()
    {
        return view('livewire.spotlight.reports.filter.talep-report-filter');
    }
}
