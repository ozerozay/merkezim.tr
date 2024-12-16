<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetTaksitReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TaksitReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_taksit;

    public ?string $reportName = 'taksit-report';

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'sale.sale_no', 'label' => 'Satış'],
            ['key' => 'client.name', 'label' => 'Danışan'],
            ['key' => 'status', 'label' => 'Durum'],
            ['key' => 'remaining', 'label' => 'Kalan'],
            ['key' => 'total', 'label' => 'Toplam'],
            ['key' => 'client_taksits_locks_count', 'label' => 'Kilit'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetTaksitReportAction::run($this->filters, $this->sortBy);
    }
}
