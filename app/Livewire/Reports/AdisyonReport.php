<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetAdisyonReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AdisyonReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_adisyon;

    public ?string $reportName = 'adisyon-report';

    public array $sortBy = ['column' => 'date', 'direction' => 'desc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'unique_id', 'label' => 'Id'],
            ['key' => 'branch.name', 'label' => 'Şube', 'sortable' => 'false'],
            ['key' => 'client.name', 'label' => 'Danışan', 'sortable' => 'false'],
            ['key' => 'user.name', 'label' => 'Kullanıcı', 'sortBy' => 'user_id'],
            ['key' => 'price', 'label' => 'Tutar'],
            ['key' => 'message', 'label' => 'Mesaj'],
            ['key' => 'staff_names', 'label' => 'Personel'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetAdisyonReportAction::run($this->filters, $this->sortBy);
    }
}
