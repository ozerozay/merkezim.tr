<?php

namespace App\Livewire\Reports\Filter;

use App\Traits\ReportFilterHandler;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CouponReportFilter extends SlideOver
{
    use ReportFilterHandler;

    public $date_range;

    public $date_range_end;

    public $staffs = [];

    public $branches = [];

    public function render()
    {
        return view('livewire.spotlight.reports.filter.coupon-report-filter');
    }
}
