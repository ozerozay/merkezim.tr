<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetSaleReportAction;
use App\Enum\ReportType;
use App\Models\UserReport;
use App\Traits\LiveHelper;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class SaleReport extends Component
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $filters = [];

    public $data = [];

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
                ->where('type', ReportType::report_sale->name)
                ->first()
                : UserReport::query()
                    ->where('unique_id', $this->report)
                    ->where('type', ReportType::report_sale->name)
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

    public function getHeaders(): array
    {
        return [
            ['key' => 'sale_no', 'label' => 'No'],
            ['key' => 'date', 'label' => 'Tarih'],
            ['key' => 'branch.name', 'label' => 'Şube'],
            ['key' => 'client.name', 'label' => 'Danışan'],
            ['key' => 'unique_id', 'label' => 'ID'],
            ['key' => 'status', 'label' => 'Durum'],
            ['key' => 'expire_date', 'label' => 'Bitiş Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'taksits_late_remaining', 'label' => 'Gecikmiş Ödeme', 'sortable' => false, 'format' => fn ($row, $field) => $field ? LiveHelper::price_text($field) : '-'],
            ['key' => 'taksits_remaining', 'label' => 'Kalan Ödeme', 'sortable' => false, 'format' => fn ($row, $field) => $field ? LiveHelper::price_text($field) : '-'],
            ['key' => 'tahsilat', 'label' => 'Tahsilat', 'sortable' => false, 'format' => fn ($row, $field) => $field ? LiveHelper::price_text($field) : '-'],
            ['key' => 'price', 'label' => 'Tutar', 'format' => fn ($row, $field) => $field ? LiveHelper::price_text($field) : '-'],
            ['key' => 'staff', 'label' => 'Personel', 'sortable' => false],
        ];
    }

    #[On('report-sale-filter')]
    public function filter($filters)
    {
        $this->filters = $filters;
    }

    public function resetFilters()
    {
        $this->filters = [];
    }

    #[Computed()]
    public function getReport()
    {
        return GetSaleReportAction::run($this->filters, $this->sortBy);
    }

    public function render()
    {
        return view('livewire.spotlight.reports.sale-report', [
            'filters' => $this->filters,
            'report_data' => count($this->filters) > 0 ? $this->getReport() : [],
            'headers' => $this->getHeaders(),
            'sortBy' => $this->sortBy,
        ]);
    }
}
