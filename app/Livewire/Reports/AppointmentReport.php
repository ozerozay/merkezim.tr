<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Report\GetAppointmentReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AppointmentReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_appointment;

    public ?string $reportName = 'appointment-report';

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'type', 'label' => 'Çeşit'],
            ['key' => 'branch.name', 'label' => 'Şube'],
            ['key' => 'client.name', 'label' => 'Danışan'],
            ['key' => 'serviceCategory.name', 'label' => 'Kategori'],
            ['key' => 'status', 'label' => 'Durum'],
            ['key' => 'serviceRoom.name', 'label' => 'Oda'],
            ['key' => 'date', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'date_start', 'label' => 'Başlangıç', 'format' => ['date', 'H:i']],
            ['key' => 'date_end', 'label' => 'Bitiş', 'format' => ['date', 'H:i']],
            ['key' => 'service_names', 'label' => 'Hizmetler'],
            ['key' => 'finish_service_names', 'label' => 'Hizmet(Bitti)'],
            ['key' => 'finish_user.name', 'label' => 'Personel'],
        ];
    }

    #[Computed()]
    public function getReport()
    {
        return GetAppointmentReportAction::run($this->filters, $this->sortBy);
    }
}
