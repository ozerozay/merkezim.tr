<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetKasaReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class KasaReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_kasa;

    public ?string $reportName = 'kasa-report';

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'branch.name', 'label' => 'Şube', 'sortBy' => 'branch_id'],
            ['key' => 'kasa.name', 'label' => 'Kasa', 'sortBy' => 'kasa_id'],
            ['key' => 'client.name', 'label' => 'Danışan', 'sortBy' => 'client_id'],
            ['key' => 'type', 'label' => 'Çeşit'],
            ['key' => 'masraf.name', 'label' => 'Masraf', 'sortBy' => 'masraf_id'],
            ['key' => 'price', 'label' => 'Tutar'],
            ['key' => 'user.name', 'label' => 'Kullanıcı'],
            ['key' => 'message', 'label' => 'Açıklama'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetKasaReportAction::run($this->filters, $this->sortBy);
    }
}
