<?php

use App\Actions\Client\GetClientSales;
use App\Models\Sale;
use App\SaleStatus;
use App\Traits\LiveHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithPagination, \App\Traits\WithViewPlaceHolder;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    public function getData()
    {
        return GetClientSales::run($this->client, true, $this->sortBy);
    }

    public function headers(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'],
            ['key' => 'sale_no', 'label' => 'No', 'sortBy' => 'sale_no'],
            ['key' => 'unique_id', 'label' => 'ID', 'sortBy' => 'unique_id'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
            ['key' => 'price', 'label' => 'Tutar', 'sortBy' => 'price'],
            ['key' => 'price_real', 'label' => 'Gerçek', 'sortBy' => 'price_real'],
            ['key' => 'pesinat', 'label' => 'Peşinat', 'sortable' => false],
            ['key' => 'total_late_payment', 'label' => 'Gecikmiş', 'sortable' => false],
            ['key' => 'total_taksit_remaining', 'label' => 'Kalan Taksit', 'sortable' => false],
            ['key' => 'total_taksit', 'label' => 'Toplam Taksit', 'sortable' => false],
            ['key' => 'total_service_remaining', 'label' => 'Seans', 'sortable' => false],
            ['key' => 'total_service', 'label' => 'Seans', 'sortable' => false],
        ];
    }

    public function headersTaksits(): array
    {
        return [
            ['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
            ['key' => 'sale.unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'],
            ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'],
            ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total'],
        ];
    }

    public function headersServices(): array
    {
        return [
            ['key' => 'service.name', 'label' => 'Hizmet', 'sortBy' => 'service_id'],
            ['key' => 'date_human_created', 'label' => 'Tarih', 'sortBy' => 'created_at'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
            ['key' => 'sale.unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'],
            ['key' => 'package.name', 'label' => 'Paket', 'sortBy' => 'sale_id'],
            ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total'],
            ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'],
            ['key' => 'gift', 'label' => 'Hediye', 'sortBy' => 'gift'],
            ['key' => 'message', 'label' => 'Açıklama', 'sortBy' => 'message'],
        ];
    }

    public function showSettings($id): void
    {
        $this->dispatch('drawer-sale-update-id', $id)->to('components.drawers.drawer_sale');
        $this->editing = true;
    }

    public function with()
    {
        return [
            'data' => $this->getData(),
            'headers' => $this->headers(),
            'headersTaksits' => $this->headersTaksits(),
            'headersServices' => $this->headersServices(),
            'statistic' => []
        ];
    }
};

?>
<div>
    <livewire:components.card.statistic.card_statistic :data="$statistic"/>
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
                  icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline"/>
    </div>
    @if ($view)
        <div>
            <x-card>
                <x-table :headers="$headers" :rows="$data" wire:model="expanded" :sort-by="$sortBy" striped
                         with-pagination expandable>
                    @scope('expansion', $sale, $headersTaksits, $headersServices)
                    <div class="bg-base-200 p-8 font-bold">

                        <x-table :headers="$headersTaksits" :rows="$sale->clientTaksits" striped>
                            <x-slot:empty>
                                <x-icon name="o-cube" label="Taksit bulunmuyor."/>
                            </x-slot:empty>
                            @scope('cell_sale.unique_id', $taksit)
                            {{ $taksit->sale->unique_id }}
                            @endscope
                            @scope('cell_status', $taksit)
                            <x-badge :value="$taksit->status->label()" class="badge-{{ $taksit->status->color() }}"/>
                            @endscope
                            @scope('cell_total', $taksit)
                            {{ LiveHelper::price_text($taksit->total) }}
                            @endscope
                            @scope('cell_remaining', $taksit)
                            {{ LiveHelper::price_text($taksit->remaining) }}
                            @endscope
                        </x-table>
                        <x-hr/>
                        <x-table :headers="$headersServices" :rows="$sale->clientServices"
                                 striped
                        >
                            <x-slot:empty>
                                <x-icon name="o-cube" label="Hizmet bulunmuyor."/>
                            </x-slot:empty>
                            @scope('cell_sale.unique_id', $service)
                            {{ $service->sale->unique_id ?? 'Yok' }}
                            @endscope
                            @scope('cell_package.name', $service)
                            {{ $service->package->name ?? 'Yok' }}
                            @endscope
                            @scope('cell_status', $service)
                            <x-badge :value="$service->status->label()" class="badge-{{ $service->status->color() }}"/>
                            @endscope
                            @scope('cell_gift', $service)
                            @if ($service->gift)
                                <x-icon name="o-check" class="text-green-500"/>
                            @endif
                            @endscope
                            @scope('cell_remaining', $service)
                            @if ($service->remaining < 1)
                                <x-icon name="o-x-circle" class="text-red-500"/>
                            @else
                                {{ $service->remaining }}
                            @endif
                            @endscope

                        </x-table>
                    </div>
                    @endscope
                    @scope('cell_status', $sale)
                    <x-badge :value="$sale->status->label()" class="badge-{{ $sale->status->color() }}"/>
                    @endscope
                    @scope('cell_unique_id', $taksit)
                    {{ $taksit->sale_no }} - {{ $taksit->unique_id  }}
                    @endscope
                    @scope('cell_price', $taksit)
                    {{ LiveHelper::price_text($taksit->price) }}
                    @endscope
                    @scope('cell_pesinat', $taksit)
                    {{ LiveHelper::price_text($taksit->pesinat) }}
                    @endscope
                    @scope('cell_total_late_payment', $taksit)
                    {{ LiveHelper::price_text($taksit->total_late_payment) }}
                    @endscope
                    @scope('cell_price_real', $taksit)
                    {{ LiveHelper::price_text($taksit->price_real) }}
                    @endscope
                    @scope('cell_total_taksit_remaining', $taksit)
                    {{ LiveHelper::price_text($taksit->total_taksit_remaining) }}
                    @endscope
                    @scope('cell_total_taksit', $taksit)
                    {{ LiveHelper::price_text($taksit->total_taksit) }}
                    @endscope
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Satış bulunmuyor."/>
                    </x-slot:empty>
                    @can('sale_process')
                        @scope('actions', $sale)
                        <x-button icon="tabler.settings"
                                  wire:click="showSettings({{ $sale->id }})"
                                  class="btn-circle btn-sm btn-primary"/>
                        @endscope
                    @endcan
                </x-table>
            </x-card>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($data as $sale)
                        <x-card title="{{ $sale->sale_no }} - {{ $sale->date }}" separator class="mb-2">
                            @can('sale_process')
                                <x-slot:menu>
                                    <x-button icon="tabler.settings"
                                              wire:click="showSettings({{ $sale->id }})"
                                              class="btn-circle btn-sm btn-primary"/>

                                </x-slot:menu>
                            @endcan
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Durum
                                </x-slot:value>
                                <x-slot:actions>
                                    <x-badge :value="$sale->status->label()"
                                             class="badge-{{ $sale->status->color() }}"/>
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Tarih
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ $sale->date }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Benzersiz
                                </x-slot:value>
                                <x-slot:actions>
                                    {{$sale->sale_no}} - {{ $sale->unique_id }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Tutar
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ LiveHelper::price_text($sale->price) }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Gerçek Tutar
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ LiveHelper::price_text($sale->price_real) }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Peşinat
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ LiveHelper::price_text($sale->pesinat) }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Toplam
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ LiveHelper::price_text($sale->total_taksit) }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Gecikmiş
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ LiveHelper::price_text($sale->late_payment) }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Kalan
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ LiveHelper::price_text($sale->total_taksit_remaining) }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Toplam Seans
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ $sale->total_service }}
                                </x-slot:actions>
                            </x-list-item>
                            <x-list-item :item="$sale">
                                <x-slot:value>
                                    Kalan Seans
                                </x-slot:value>
                                <x-slot:actions>
                                    {{ $sale->total_service_remaining }}
                                </x-slot:actions>
                            </x-list-item>
                        </x-card>
                    @endforeach
                </div>
            @endif
            @can('sale_process')
                <livewire:components.drawers.drawer_sale wire:model="editing"/>
            @endcan
        </div>
