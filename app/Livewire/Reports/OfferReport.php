<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetOfferReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class OfferReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_offer;

    public ?string $reportName = 'offer-report';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'created_at', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'unique_id', 'label' => 'Id'],
            ['key' => 'client.client_branch.name', 'label' => 'Şube', 'sortable' => 'false'],
            ['key' => 'client.name', 'label' => 'Danışan', 'sortable' => 'false'],
            ['key' => 'user.name', 'label' => 'Kullanıcı', 'sortBy' => 'user_id'],
            ['key' => 'price', 'label' => 'Tutar'],
            ['key' => 'status', 'label' => 'Durum'],
            ['key' => 'message', 'label' => 'Açıklama'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetOfferReportAction::run($this->filters, $this->sortBy);
    }
}
