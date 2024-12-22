<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetTalepReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TalepReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_talep;

    public ?string $reportName = 'talep-report';

    public array $sortBy = ['column' => 'date', 'direction' => 'desc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'branch.name', 'label' => 'Şube', 'sortBy' => 'branch_id'],
            ['key' => 'user.name', 'label' => 'Kullanıcı', 'sortBy' => 'user_id'],
            ['key' => 'talep_processes_count', 'label' => 'İşlem'],
            ['key' => 'type', 'label' => 'Tip'],
            ['key' => 'status', 'label' => 'Durum'],
            ['key' => 'message', 'label' => 'Mesaj'],

        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetTalepReportAction::run($this->filters, $this->sortBy);
    }
}
