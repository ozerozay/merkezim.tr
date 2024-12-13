<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetSaleReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ServiceReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_service;

    public ?string $reportName = 'service-report';

    public function getHeaders(): array
    {
        return [
            ['key' => 'created_at', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'sale.sale_no', 'label' => 'Satış'],
            ['key' => 'service.name', 'label' => 'Hizmet'],
            ['key' => 'client.name', 'label' => 'Danışan'],
            ['key' => 'status', 'label' => 'Durum'],
            ['key' => 'remaining', 'label' => 'Kalan'],
            ['key' => 'total', 'label' => 'Toplam'],
            ['key' => 'gift', 'label' => 'Hediye'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetSaleReportAction::run($this->filters, $this->sortBy);
    }
}
