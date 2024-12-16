<div>
    <x-report-page title="Taksit Raporu" :filters="$filters" name="{{ \App\Enum\ReportType::report_taksit }}"
        namee="taksit-report" :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @scope('cell_status', $taksit)
                    <x-badge value="{{ $taksit->status->label() }}" class="badge-{{ $taksit->status->color() }}" />
                @endscope
                @can('taksit_process')
                    @scope('actions', $taksit)
                        <x-button icon="tabler.settings" wire:key="settings-{{ rand(100000000, 999999999) }}"
                            wire:click="$dispatch('slide-over.open', {component: 'modals.client.taksit-modal', arguments : {'taksit' : {{ $taksit->id }}}})"
                            class="btn-circle btn-sm btn-primary" />
                    @endscope
                @endcan
                @scope('cell_client.name', $taksit)
                    <a class="link link-primary" target="_blank"
                        href="{{ route('admin.client.profil.index', ['user' => $taksit->client_id]) }}">{{ $taksit->client->name }}</a>
                @endscope
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
