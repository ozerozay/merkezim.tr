<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetSaleProductReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SaleProductReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_kasa;

    public ?string $reportName = 'sale-product-report';

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'branch.name', 'label' => 'Şube', 'sortBy' => 'branch_id'],
            ['key' => 'client.name', 'label' => 'Kasa', 'sortBy' => 'client_id'],
            ['key' => 'unique_id', 'label' => 'Danışan', 'sortBy' => 'unique_id'],
            ['key' => 'price', 'label' => 'Tutar'],
            ['key' => 'sale_product_items_count', 'label' => 'Ürün'],
            ['key' => 'user.name', 'label' => 'Kullanıcı'],
            ['key' => 'message', 'label' => 'Açıklama'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetSaleProductReportAction::run($this->filters, $this->sortBy);
    }
}
