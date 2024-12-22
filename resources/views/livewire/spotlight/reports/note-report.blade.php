<div>
    <x-report-page title="Not Raporu" :filters="$filters" name="{{ \App\Enum\ReportType::report_note }}"
                   namee="note-report" :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @can('note_process')
                    @scope('actions', $service)
                    <x-button icon="tabler.settings" wire:key="settincgs-{{ rand(100000000, 999999999) }}"
                              wire:click="delete({{ $service->id }})"
                              wire:confirm="Emin misiniz ?"
                              spinner
                              class="btn-circle btn-sm btn-primary"/>
                    @endscope
                @endcan
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
