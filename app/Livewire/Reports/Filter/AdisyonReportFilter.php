<?php

namespace App\Livewire\Reports\Filter;

use App\Traits\ReportFilterHandler;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AdisyonReportFilter extends SlideOver
{
    use ReportFilterHandler;

    public $date_range;

    public $branches = [];

    public $staffs = [];

    public $client;

    public function render()
    {
        return view('livewire.spotlight.reports.filter.adisyon-report-filter');
    }
}
