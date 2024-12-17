<?php

namespace App\Livewire\Reports\Filter;

use App\Traits\ReportFilterHandler;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleProductReportFilter extends SlideOver
{
    use ReportFilterHandler;

    public $branches = [];

    public $date_range;

    public $staff_create = [];

    public $client = null;

    public $products = [];

    public function render()
    {
        return view('livewire.spotlight.reports.filter.sale-product-report-filter');
    }
}
