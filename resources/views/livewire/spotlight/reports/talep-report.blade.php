<div>
    <x-report-page title="Talep Raporu" :filters="$filters" name="{{ \App\Enum\ReportType::report_talep }}"
                   namee="talep-report" :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @can('talep_process')
                    @scope('actions', $service)
                    <x-button icon="tabler.settings" wire:key="settincgs-{{ rand(100000000, 999999999) }}"
                              wire:click="$dispatch('slide-over.open', {component: 'modals.talep.talep-modal', arguments : {'talep' : {{ $service->id }}}})"
                              class="btn-circle btn-sm btn-primary"/>
                    @endscope
                @endcan
                @scope('cell_status', $sale)
                <x-badge value="{{ $sale->status->label() }}" class="badge-{{ $sale->status->color() }}"/>
                @endscope
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
