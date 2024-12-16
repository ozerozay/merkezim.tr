<div>
    <x-report-page title="Randevu Raporu" :filters="$filters" name="{{ \App\Enum\ReportType::report_appointment }}"
        namee="appointment-report" :favori="$favori">
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @scope('cell_status', $appointment)
                    <x-badge value="{{ $appointment->status->label() }}" class="badge-{{ $appointment->status->color() }}" />
                @endscope
                @can('appointment_process')
                    @scope('actions', $appointment)
                        <x-button icon="tabler.settings" wire:key="settings-{{ rand(100000000, 999999999) }}"
                            wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments : {'appointment' : {{ $appointment->id }}}})"
                            class="btn-circle btn-sm btn-primary" />
                    @endscope
                @endcan
                @scope('cell_client.name', $appointment)
                    <a class="link link-primary" target="_blank"
                        href="{{ route('admin.client.profil.index', ['user' => $appointment->client_id]) }}">{{ $appointment->client->name }}</a>
                @endscope
                @scope('cell_type', $appointment)
                    {{ $appointment->type->label() }}
                @endscope
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-report-page>
</div>
