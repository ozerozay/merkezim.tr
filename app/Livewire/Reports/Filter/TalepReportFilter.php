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

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function render()
    {
        return view('livewire.spotlight.reports.filter.talep-report-filter');
    }
}
