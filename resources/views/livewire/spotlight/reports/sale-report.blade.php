<div>
    <x-header title="Rapor - Satış" separator progress-indicator>
        <x-slot:actions>
            <div x-data="{ filters: @js($filters) }">
                <x-button label="Filtrele" icon="tabler.filter" class="btn-primary"
                    wire:click="$dispatch('slide-over.open', {'component': 'reports.filter.sale-report-filter', 'arguments': {'filters': filters}})"
                    responsive />
                @if (count($filters) > 0)
                    <x-button label="Sıfırla" wire:click="resetFilters" icon="tabler.x" class="btn-outline" responsive />
                    <x-dropdown label="İşlemler" icon="tabler.settings" class="btn-primary btn-outline">
                        <x-menu-item title="SMS Gönder" />
                        <x-menu-item title="Teklif Oluştur" />
                        <x-menu-item title="Kupon Oluştur" />
                    </x-dropdown>
                    <x-dropdown label="Gönder" icon="tabler.mail-forward" class="btn-primary btn-outline">
                        <x-menu-item title="Mail - PDF" />
                        <x-menu-item title="Mail - EXCEL" />
                    </x-dropdown>
                    @if ($favori)
                        <x-button label="Sil" wire:click="deleteReport('{{ $report }}')"
                            wire:confirm="Silmek istediğinizden emin misiniz ?" icon="tabler.x"
                            class="btn-error btn-outline" />
                    @else
                        <x-button label="Kaydet"
                            wire:click="$dispatch('slide-over.open', {'component': 'modals.report.save-report-modal', 'arguments': {'filters': filters, 'type': '{{ \App\Enum\ReportType::report_sale->name }}'}})"
                            icon="tabler.heart-plus" class="btn-error btn-outline" />
                    @endif
                @endif
            </div>
        </x-slot:actions>
    </x-header>
    <x-card>
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @scope('cell_staff', $sale)
                    {{ $sale->staff_names() }}
                @endscope
                @scope('cell_status', $sale)
                    <x-badge value="{{ $sale->status->label() }}" class="badge-{{ $sale->status->color() }}" />
                @endscope
                @can('sale_process')
                    @scope('actions', $sale)
                        <x-button icon="tabler.settings" wire:key="settings-{{ rand(100000000, 999999999) }}"
                            wire:click="$dispatch('slide-over.open', {component: 'modals.client.sale-modal', arguments : {'sale' : {{ $sale->id }}}})"
                            class="btn-circle btn-sm btn-primary" />
                    @endscope
                @endcan
                @scope('cell_client.name', $sale)
                    <a class="link link-primary" target="_blank"
                        href="{{ route('admin.client.profil.index', ['user' => $sale->client_id]) }}">{{ $sale->client->name }}</a>
                @endscope
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-card>
</div>
