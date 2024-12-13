@props([
    'title' => null,
    'filters' => null,
    'report' => null,
    'name' => null,
    'namee' => null,
    'favori' => null,
])
<div>
    <x-header title="{{ $title ?? '' }}" separator progress-indicator>
        <x-slot:actions>
            <div x-data="{ filters: @js($filters) }">
                <x-button label="Filtrele" icon="tabler.filter" class="btn-primary"
                    wire:click="$dispatch('slide-over.open', {'component': 'reports.filter.{{ $namee }}-filter', 'arguments': {'filters': filters}})"
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
                            wire:click="$dispatch('slide-over.open', {'component': 'modals.report.save-report-modal', 'arguments': {'filters': filters, 'type': '{{ $name }}'}})"
                            icon="tabler.heart-plus" class="btn-error btn-outline" />
                    @endif
                @endif
            </div>
        </x-slot:actions>
    </x-header>
    <x-card>
        {{ $slot }}
    </x-card>
</div>
