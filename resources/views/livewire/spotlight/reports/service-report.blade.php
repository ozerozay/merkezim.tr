<div>
    <x-report-page title="Hizmet Raporu" :filters="$filters" name="{{ \App\Enum\ReportType::report_service }}"
        namee="service-report" :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @scope('cell_status', $service)
                    <x-badge value="{{ $service->status->label() }}" class="badge-{{ $service->status->color() }}" />
                @endscope
                @scope('cell_gift', $service)
                    @if ($service->gift)
                        <x-icon name="tabler.check" />
                    @else
                        -
                    @endif
                @endscope
                @can('service_process')
                    @scope('actions', $service)
                        <x-button icon="tabler.settings" wire:key="settings-{{ rand(100000000, 999999999) }}"
                            wire:click="$dispatch('slide-over.open', {component: 'modals.client.service-modal', arguments : {'service' : {{ $service->id }}}})"
                            class="btn-circle btn-sm btn-primary" />
                    @endscope
                @endcan
                @scope('cell_client.name', $service)
                    <a class="link link-primary" target="_blank"
                        href="{{ route('admin.client.profil.index', ['user' => $service->client_id]) }}">{{ $service->client->name }}</a>
                @endscope
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
