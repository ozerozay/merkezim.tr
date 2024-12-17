<?php

namespace App\Livewire\Reports\Filter;

use App\Traits\ReportFilterHandler;
use WireElements\Pro\Components\SlideOver\SlideOver;

class OfferReportFilter extends SlideOver
{
    use ReportFilterHandler;

    public $date_range;

    public $date_range_expire;

    public $branches = [];

    public $staffs = [];

    public $statuses = [];

    public function render()
    {
        return view('livewire.spotlight.reports.filter.offer-report-filter');
    }
}
