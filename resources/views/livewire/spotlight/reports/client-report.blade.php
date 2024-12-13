<div>
    <x-header title="Rapor - Danışan" separator progress-indicator>
        <x-slot:actions>
            <div x-data="{ filters: @js($filters) }">
                <x-button label="Filtrele" icon="tabler.filter"
                    class="btn-primary {{ count($filters) > 0 ? '' : 'btn-outline' }}"
                    wire:click="$dispatch('slide-over.open', {'component': 'reports.filter.client-report-filter', 'arguments': {'filters': filters}})" />
                @if (count($filters) > 0)
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
                            wire:click="$dispatch('slide-over.open', {'component': 'modals.report.save-report-modal', 'arguments': {'filters': filters, 'type': '{{ \App\Enum\ReportType::report_client->name }}'}})"
                            icon="tabler.heart-plus" class="btn-error btn-outline" />
                    @endif
                @endif
            </div>
        </x-slot:actions>
    </x-header>
    <x-card>
        @if (count($filters) > 0)
            <x-table :headers="$headers" striped :rows="$report_data" :sort-by="$sortBy" with-pagination>
                @scope('cell_etiket', $user)
                    {{ $user->label_names() }}
                @endscope
                @scope('cell_name', $sale)
                    <x-button label="{{ $sale->name }}" class="btn-sm"
                        link="{{ route('admin.client.profil.index', ['user' => $sale->id]) }}" external icon="o-link" />
                @endscope
            </x-table>
        @else
            Filtreleyerek raporu çalıştırabilirsiniz.
        @endif
    </x-card>
</div>
