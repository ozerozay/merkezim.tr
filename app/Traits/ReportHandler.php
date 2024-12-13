<?php

namespace App\Traits;

use App\Models\UserReport;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

trait ReportHandler
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $filters = [];

    public array $sortBy = ['column' => 'date', 'direction' => 'desc'];

    #[Url()]
    public $report = null;

    public $favori = false;

    public function mount()
    {

        if ($this->report != null) {
            $report_check = (! auth()->user()->hasRole('admin'))
            ? UserReport::query()
                ->where('unique_id', $this->report)
                ->where('user_id', auth()->user()->id)
                ->where('type', $this->reportType->name)
                ->first()
                : UserReport::query()
                    ->where('unique_id', $this->report)
                    ->where('type', $this->reportType->name)
                    ->first();

            if ($report_check) {
                $this->filters = $report_check->data;
                $this->favori = true;
            }

        }
    }

    public function deleteReport($id)
    {
        UserReport::where('unique_id', $id)->delete();
        $this->filters = [];
        $this->favori = false;
        $this->report = null;
    }

    #[On('report-filter')]
    public function filter($filters)
    {
        $this->filters = $filters;
    }

    public function resetFilters()
    {
        $this->filters = [];
    }

    public function render()
    {
        return view("livewire.spotlight.reports.{$this->reportName}", [
            'filters' => $this->filters,
            'report_data' => count($this->filters) > 0 ? $this->getReport() : [],
            'headers' => $this->getHeaders(),
            'sortBy' => $this->sortBy,
        ]);
    }
}
