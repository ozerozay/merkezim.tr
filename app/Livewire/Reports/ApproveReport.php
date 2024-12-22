<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetApproveReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ApproveReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_approve;

    public ?string $reportName = 'approve-report';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'created_at', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'status', 'label' => 'Durum'],
            ['key' => 'type', 'label' => 'Çeşit'],
            ['key' => 'user.name', 'label' => 'Kullanıcı', 'sortBy' => 'user_id'],
            ['key' => 'message', 'label' => 'Mesaj'],
            ['key' => 'approved_by.name', 'label' => 'Onaylayan', 'sortBy' => 'approved_by'],
            ['key' => 'approve_message', 'label' => 'Onay Mesajı'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetApproveReportAction::run($this->filters, $this->sortBy);
    }
}
