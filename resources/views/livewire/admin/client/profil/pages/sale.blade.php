<?php

use App\Actions\Client\GetClientSales;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use \App\Traits\WithViewPlaceHolder, \Livewire\WithoutUrlPagination, Toast, WithPagination;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    protected $listeners = [
        'refresh-client-sales' => '$refresh',
    ];

    public function getData()
    {
        return GetClientSales::run($this->client, true, $this->sortBy);
    }

    public function headers(): array
    {
        return [['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'], ['key' => 'sale_no', 'label' => 'No', 'sortBy' => 'sale_no'], ['key' => 'unique_id', 'label' => 'ID', 'sortBy' => 'unique_id'], ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'], ['key' => 'price', 'label' => 'Tutar', 'sortBy' => 'price'], ['key' => 'price_real', 'label' => 'Gerçek', 'sortBy' => 'price_real'], ['key' => 'pesinat', 'label' => 'Peşinat', 'sortable' => false], ['key' => 'total_late_payment', 'label' => 'Gecikmiş', 'sortable' => false], ['key' => 'total_taksit_remaining', 'label' => 'Kalan Taksit', 'sortable' => false], ['key' => 'total_taksit', 'label' => 'Toplam Taksit', 'sortable' => false], ['key' => 'total_service_remaining', 'label' => 'Seans', 'sortable' => false], ['key' => 'total_service', 'label' => 'Seans', 'sortable' => false]];
    }

    public function headersTaksits(): array
    {
        return [['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'], ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'], ['key' => 'sale.unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'], ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'], ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total']];
    }

    public function headersServices(): array
    {
        return [['key' => 'service.name', 'label' => 'Hizmet', 'sortBy' => 'service_id'], ['key' => 'date_human_created', 'label' => 'Tarih', 'sortBy' => 'created_at'], ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'], ['key' => 'sale.unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'], ['key' => 'package.name', 'label' => 'Paket', 'sortBy' => 'sale_id'], ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total'], ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'], ['key' => 'gift', 'label' => 'Hediye', 'sortBy' => 'gift'], ['key' => 'message', 'label' => 'Açıklama', 'sortBy' => 'message']];
    }

    public function showSettings($id): void
    {
        $this->dispatch('drawer-sale-update-id', $id)->to('components.drawers.drawer_sale');
        $this->editing = true;
    }

    public function with(): array
    {
        return [
            'data' => $this->getData(),
            'headers' => $this->headers(),
            'headersTaksits' => $this->headersTaksits(),
            'headersServices' => $this->headersServices(),
            'statistic' => [],
        ];
    }
};

?>
<div>
    <livewire:components.card.statistic.card_statistic :data="$statistic" wire:key="xx{{ rand(100000000, 999999999) }}" />
    
    <!-- Filtreler ve Görünüm Seçenekleri -->
    <div class="flex justify-between items-center mb-4 mt-5">
        <p class="text-sm text-base-content/70">Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <div class="flex items-center gap-2">
            <x-button 
                wire:click="changeView" 
                :icon="$view == 'table' ? 'tabler.list' : 'tabler.table'" 
                :label="$view == 'table' ? 'LİSTE' : 'TABLO'"
                class="btn-sm btn-outline" />
        </div>
    </div>

    @if ($view == 'table')
        <!-- Tablo Görünümü -->
        <div class="card bg-base-100">
            <div class="card-body p-0">
                <x-table 
                    :headers="$headers" 
                    :rows="$data" 
                    wire:model="expanded" 
                    :sort-by="$sortBy" 
                    striped 
                    with-pagination 
                    expandable 
                    wire:key="{{ str()->random(50) }}">
                    
                    @scope('expansion', $sale, $headersTaksits, $headersServices)
                        <div class="bg-base-200/50 p-6 space-y-6">
                            <!-- Taksitler -->
                            <div class="space-y-2">
                                <h3 class="font-medium text-base">Taksit Detayları</h3>
                                <x-table 
                                    :headers="$headersTaksits" 
                                    :rows="$sale->clientTaksits" 
                                    striped
                                    wire:key="table-{{ rand(100000000, 999999999) }}">
                                    <x-slot:empty>
                                        <div class="flex flex-col items-center py-6">
                                            <x-icon name="o-cube" class="w-12 h-12 text-base-content/30" />
                                            <p class="mt-2 text-sm text-base-content/60">Taksit bulunmuyor</p>
                                        </div>
                                    </x-slot:empty>
                                    @scope('cell_sale.unique_id', $taksit)
                                        {{ $taksit->sale->unique_id }}
                                    @endscope
                                    @scope('cell_status', $taksit)
                                        <x-badge :value="$taksit->status->label()" class="badge-{{ $taksit->status->color() }}" />
                                    @endscope
                                    @scope('cell_total', $taksit)
                                        @price($taksit->total)
                                    @endscope
                                    @scope('cell_remaining', $taksit)
                                        @price($taksit->remaining)
                                    @endscope
                                </x-table>
                            </div>

                            <!-- Hizmetler -->
                            <div class="space-y-2">
                                <h3 class="font-medium text-base">Hizmet Detayları</h3>
                                <x-table 
                                    :headers="$headersServices" 
                                    :rows="$sale->clientServices" 
                                    striped
                                    wire:key="service-{{ rand(10000000, 99999999) }}">
                                    <x-slot:empty>
                                        <div class="flex flex-col items-center py-6">
                                            <x-icon name="o-cube" class="w-12 h-12 text-base-content/30" />
                                            <p class="mt-2 text-sm text-base-content/60">Hizmet bulunmuyor</p>
                                        </div>
                                    </x-slot:empty>
                                    <!-- Hizmet hücreleri -->
                                    @scope('cell_sale.unique_id', $service)
                                        {{ $service->sale->unique_id ?? 'Yok' }}
                                    @endscope
                                    @scope('cell_package.name', $service)
                                        {{ $service->package->name ?? 'Yok' }}
                                    @endscope
                                    @scope('cell_status', $service)
                                        <x-badge :value="$service->status->label()" class="badge-{{ $service->status->color() }}" />
                                    @endscope
                                    @scope('cell_gift', $service)
                                        @if ($service->gift)
                                            <x-icon name="o-check" class="w-5 h-5 text-success" />
                                        @endif
                                    @endscope
                                    @scope('cell_remaining', $service)
                                        @if ($service->remaining < 1)
                                            <x-icon name="o-x-circle" class="w-5 h-5 text-error" />
                                        @else
                                            {{ $service->remaining }}
                                        @endif
                                    @endscope
                                </x-table>
                            </div>
                        </div>
                    @endscope

                    <!-- Ana tablo hücreleri -->
                    @scope('cell_status', $sale)
                        <x-badge :value="$sale->status->label()" class="badge-{{ $sale->status->color() }}" />
                    @endscope
                    <!-- ... diğer hücreler ... -->

                    @can('sale_process')
                        @scope('actions', $sale)
                            <x-button 
                                icon="tabler.settings" 
                                wire:click="$dispatch('slide-over.open', {component: 'modals.client.sale-modal', arguments: {'sale': {{ $sale->id }}}})"
                                class="btn-circle btn-sm btn-primary" />
                        @endscope
                    @endcan
                </x-table>
            </div>
        </div>
    @else
        <!-- Liste Görünümü -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @foreach ($data as $sale)
                <div class="card bg-base-100 hover:shadow-lg transition-all">
                    <div class="card-body p-4">
                        <!-- Başlık ve Durum -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-medium">{{ $sale->sale_no }}</h3>
                                    <span class="text-xs text-base-content/60">#{{ $sale->unique_id }}</span>
                                </div>
                                <p class="text-sm text-base-content/60">{{ $sale->date }}</p>
                            </div>
                            <x-badge :value="$sale->status->label()" class="badge-{{ $sale->status->color() }}" />
                        </div>

                        <!-- Detaylar -->
                        <div class="space-y-3">
                            <!-- Fiyat Bilgileri -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <x-list-item :item="$sale">
                                    <x-slot:value>Tutar</x-slot:value>
                                    <x-slot:actions>@price($sale->price)</x-slot:actions>
                                </x-list-item>

                                <x-list-item :item="$sale">
                                    <x-slot:value>Gerçek Tutar</x-slot:value>
                                    <x-slot:actions>@price($sale->price_real)</x-slot:actions>
                                </x-list-item>
                            </div>

                            <!-- Ödeme Bilgileri -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <x-list-item :item="$sale">
                                    <x-slot:value>Peşinat</x-slot:value>
                                    <x-slot:actions>@price($sale->pesinat)</x-slot:actions>
                                </x-list-item>

                                <x-list-item :item="$sale">
                                    <x-slot:value>Gecikmiş</x-slot:value>
                                    <x-slot:actions>@price($sale->total_late_payment)</x-slot:actions>
                                </x-list-item>
                            </div>

                            <!-- Taksit Bilgileri -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <x-list-item :item="$sale">
                                    <x-slot:value>Kalan Taksit</x-slot:value>
                                    <x-slot:actions>@price($sale->total_taksit_remaining)</x-slot:actions>
                                </x-list-item>

                                <x-list-item :item="$sale">
                                    <x-slot:value>Toplam Taksit</x-slot:value>
                                    <x-slot:actions>@price($sale->total_taksit)</x-slot:actions>
                                </x-list-item>
                            </div>

                            <!-- Seans Bilgileri -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <x-list-item :item="$sale">
                                    <x-slot:value>Kalan Seans</x-slot:value>
                                    <x-slot:actions>
                                        <span @class([
                                            'font-medium',
                                            'text-error' => $sale->total_service_remaining < 1,
                                            'text-success' => $sale->total_service_remaining > 0,
                                        ])>
                                            {{ $sale->total_service_remaining }}
                                        </span>
                                    </x-slot:actions>
                                </x-list-item>

                                <x-list-item :item="$sale">
                                    <x-slot:value>Toplam Seans</x-slot:value>
                                    <x-slot:actions>{{ $sale->total_service }}</x-slot:actions>
                                </x-list-item>
                            </div>
                        </div>

                        <!-- Alt Bilgiler -->
                        <div class="mt-4 pt-4 border-t border-base-200">
                            <div class="flex flex-wrap gap-2">
                                @if($sale->total_late_payment > 0)
                                    <span class="badge badge-sm badge-error gap-1">
                                        <x-icon name="tabler.alert-circle" class="w-3 h-3" />
                                        Gecikmiş Ödeme
                                    </span>
                                @endif
                                @if($sale->total_service_remaining < 1)
                                    <span class="badge badge-sm badge-warning gap-1">
                                        <x-icon name="tabler.hourglass-empty" class="w-3 h-3" />
                                        Seanslar Bitti
                                    </span>
                                @endif
                                @if($sale->total_taksit_remaining > 0)
                                    <span class="badge badge-sm badge-info gap-1">
                                        <x-icon name="tabler.cash" class="w-3 h-3" />
                                        Taksit Devam Ediyor
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- İşlemler -->
                        @can('sale_process')
                            <div class="card-actions justify-end mt-4">
                                <x-button 
                                    icon="tabler.eye"
                                    wire:click="$dispatch('slide-over.open', {component: 'modals.client.sale-modal', arguments: {'sale': {{ $sale->id }}}})"
                                    class="btn-sm btn-ghost" />
                                <x-button 
                                    icon="tabler.settings"
                                    wire:click="$dispatch('slide-over.open', {component: 'modals.client.sale-modal', arguments: {'sale': {{ $sale->id }}}})"
                                    class="btn-sm btn-primary" />
                            </div>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
