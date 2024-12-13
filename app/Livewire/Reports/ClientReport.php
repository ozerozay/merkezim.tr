<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetClientReportAction;
use App\Enum\ReportType;
use App\Models\UserReport;
use App\Traits\LiveHelper;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ClientReport extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $filters = [];

    public $data = [];

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

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
                ->where('type', ReportType::report_client->name)
                ->first()
                : UserReport::query()
                    ->where('unique_id', $this->report)
                    ->where('type', ReportType::report_client->name)
                    ->first();

            if ($report_check) {
                $this->filters = $report_check->data;
                $this->favori = true;
            }
        }
    }

    public function getHeaders(): array
    {
        return [
            ['key' => 'client_branch.name', 'label' => 'Şube'],
            ['key' => 'unique_id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Ad'],
            ['key' => 'gender', 'label' => 'Cinsiyet', 'format' => fn ($row, $field) => $field == 1 ? 'Kadın' : 'Erkek'],
            ['key' => 'phone', 'label' => 'Tel'],
            ['key' => 'taksits_late_remaining', 'label' => 'Gecikmiş Ödeme', 'sortable' => false, 'format' => fn ($row, $field) => $field ? LiveHelper::price_text($field) : '-'],
            ['key' => 'taksits_remaining', 'label' => 'Kalan Ödeme', 'sortable' => false, 'format' => fn ($row, $field) => $field ? LiveHelper::price_text($field) : '-'],
            ['key' => 'total_sale', 'label' => 'Toplam Satış', 'sortable' => false, 'format' => fn ($row, $field) => $field ? LiveHelper::price_text($field) : '-'],
            ['key' => 'active_appointment', 'label' => 'Randevu', 'sortable' => false, 'format' => fn ($row, $field) => $field ?: '-'],
            ['key' => 'first_login', 'label' => 'Giriş', 'sortable' => false, 'format' => fn ($row, $field) => $field ? 'Yaptı' : '-'],
            ['key' => 'total_referans', 'label' => 'Referans', 'sortable' => false, 'format' => fn ($row, $field) => $field ?: '-'],
            ['key' => 'active_offer', 'label' => 'Teklif', 'sortable' => false, 'format' => fn ($row, $field) => $field ?: '-'],
            ['key' => 'etiket', 'label' => 'Etiket', 'sortable' => false],
        ];
    }

    public function deleteReport($id)
    {
        UserReport::where('unique_id', $id)->delete();
        $this->filters = [];
        $this->favori = false;
        $this->report = null;
    }

    #[On('report-client-filter')]
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
        return GetClientReportAction::run($this->filters, $this->sortBy);
    }

    public function render()
    {
        return view('livewire.spotlight.reports.client-report', [
            'filters' => $this->filters,
            'report_data' => count($this->filters) > 0 ? $this->getReport() : [],
            'headers' => $this->getHeaders(),
            'sortBy' => $this->sortBy,
        ]);
    }
}
