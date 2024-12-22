<div>
    <x-report-page title="Onay Raporu" :filters="$filters" name="{{ \App\Enum\ReportType::report_approve }}"
                   namee="approve-report"
                   :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @can('approve_process')
                    @scope('actions', $service)
                    <x-button icon="tabler.settings" wire:key="settings-{{ rand(100000000, 999999999) }}"
                              wire:click="$dispatch('slide-over.open', {component: 'modals.client.approve-modal', arguments : {'approve' : {{ $service->id }}}})"
                              class="btn-circle btn-sm btn-primary"/>
                    @endscope
                @endcan
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
