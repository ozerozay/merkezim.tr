<?php

namespace App\Livewire\Reports;

use App\Actions\Spotlight\Actions\Client\Commands\DeleteClientNoteAction;
use App\Actions\Spotlight\Actions\Report\GetNoteReportAction;
use App\Enum\ReportType;
use App\Traits\ReportHandler;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NoteReport extends Component
{
    use ReportHandler;

    public ?ReportType $reportType = ReportType::report_note;

    public ?string $reportName = 'note-report';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function getHeaders(): array
    {
        return [
            ['key' => 'created_at', 'label' => 'Tarih', 'format' => ['date', 'd/m/Y']],
            ['key' => 'client.client_branch.name', 'label' => 'Şube', 'sortable' => 'false'],
            ['key' => 'user.name', 'label' => 'Kullanıcı', 'sortBy' => 'user_id'],
            ['key' => 'message', 'label' => 'Mesaj'],
        ];
    }

    public function delete($id)
    {
        DeleteClientNoteAction::run($id);
        $this->success('Not silindi.');
    }

    #[Computed()]
    public function getReport()
    {
        return GetNoteReportAction::run($this->filters, $this->sortBy);
    }
}
