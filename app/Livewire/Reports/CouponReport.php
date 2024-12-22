<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetCouponReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CouponReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_coupon;

    public ?string $reportName = 'coupon-report';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'created_at', 'label' => 'Tarih', 'format' => ['created_at', 'd/m/Y']],
            ['key' => 'code', 'label' => 'Kod'],
            ['key' => 'client.client_branch.name', 'label' => 'Şube', 'sortable' => 'false'],
            ['key' => 'client.name', 'label' => 'Danışan', 'sortable' => 'false'],
            ['key' => 'user.name', 'label' => 'Kullanıcı', 'sortBy' => 'user_id'],
            ['key' => 'discount_type', 'label' => 'Çeşit'],
            ['key' => 'discount_amount', 'label' => 'Tutar'],
            ['key' => 'count', 'label' => 'Adet'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetCouponReportAction::run($this->filters, $this->sortBy);
    }
}
