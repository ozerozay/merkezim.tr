<?php

namespace App\Livewire\Reports\Filter;

use App\Traits\ReportFilterHandler;
use WireElements\Pro\Components\SlideOver\SlideOver;

class NoteReportFilter extends SlideOver
{
    use ReportFilterHandler;

    public $date_range;

    public $staffs = [];

    public $branches = [];

    public $client;

    public function render()
    {
        return view('livewire.spotlight.reports.filter.note-report-filter');
    }
}
