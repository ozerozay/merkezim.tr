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
    use Toast, WithPagination;

    public $client;

    public $client_sales;

    public bool $sale_edit = false;

    public ?Sale $selected_sale;

    public $view = 'table';

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public function headers()
    {
        //LiveHelper::class;

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

    public function headersTaksits()
    {
        //LiveHelper::class;

        return [
            ['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
            ['key' => 'sale.unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'],
            ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'],
            ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total'],
        ];
    }

    public function headersServices()
    {

        //SaleStatus::cancel;
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

    public function getSales(): LengthAwarePaginator
    {
        return GetClientSales::run($this->client, true, $this->sortBy);
    }

    public function with()
    {
        $sales = $this->getSales();

        $total_active_taksit_total = $sales->where('status', SaleStatus::success)->sum('total_taksit');
        $total_active_taksit_remaining = $sales->where('status', SaleStatus::success)->sum('total_taksit_remaining');
        $total_active_late_payment = $sales->where('status', SaleStatus::success)->sum('total_late_payment');
        $total_paid = $sales->where('status', SaleStatus::success)->sum('pesinat') + ($total_active_taksit_total - $total_active_taksit_remaining);
        $total_price_real = $sales->where('status', SaleStatus::success)->sum('price_real');
        $total_service = $sales->where('status', SaleStatus::success)->sum('total_service');
        $total_service_remaining = $sales->where('status', SaleStatus::success)->sum('total_service_remaining');

        return [
            'sales' => $sales,
            'headers' => $this->headers(),
            'total_active_taksit_remaining' => $total_active_taksit_remaining,
            'total_active_late_payment' => $total_active_late_payment,
            'total_paid' => $total_paid,
            'total_price_real' => $total_price_real,
            'total_service_remaining' => $total_service_remaining,
            'total_service' => $total_service,
            'headersTaksits' => $this->headersTaksits(),
            'headersServices' => $this->headersServices()
        ];
    }

    public function placeholder()
    {
        return view('livewire.components.card.loading.loading');
    }

    public function changeView(): void
    {
        $this->view = $this->view == 'table' ? 'list' : 'table';
    }

    public bool $drawerModel = false;

    public function showDrawer($sale): void
    {
        $this->dispatch('sale-drawer-update-saleID', $sale)->to('components.drawers.sale_drawer');
        $this->drawerModel = true;
    }

    public string $group = 'group1';

    public $expanded = [];
    public $expandedService = [];
};

?>
<div>
    <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <x-stat title="Toplam Tutar" value="{{ LiveHelper::price_text($total_active_taksit_remaining) }}"
                icon="o-credit-card"/>
        <x-stat title="Kalan Ödeme" value="{{ LiveHelper::price_text($total_active_taksit_remaining) }}"
                icon="o-credit-card"/>
        <x-stat title="Yapılan Ödeme" value="{{ LiveHelper::price_text($total_paid) }}" icon="o-credit-card"/>
        <x-stat title="Gecikmiş Ödeme" value="{{ LiveHelper::price_text($total_active_late_payment) }}"
                icon="o-credit-card"/>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mt-5">
        <x-stat title="Toplam Seans" :value="$total_service" icon="tabler.info-circle"/>
        <x-stat title="Kullanılan Seans" :value="($total_service - $total_service_remaining)"
                icon="tabler.info-circle"/>
        <x-stat title="Kalan Seans" :value="$total_service_remaining" class="text-red-500"
                icon="tabler.info-circle"/>
    </div>
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
                  icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline"/>

    </div>
    @if ($view == 'table')
        <div>
            <x-card>
                <x-table :headers="$headers" :rows="$sales" wire:model="expanded" :sort-by="$sortBy" striped
                         with-pagination expandable>
                    @scope('expansion', $sale, $headersTaksits, $headersServices, $expandedService)
                    <div class="bg-base-200 p-8 font-bold">
                       
                        <x-table :headers="$headersTaksits" :rows="$sale->clientTaksits" striped>
                            <x-slot:empty>
                                <x-icon name="o-cube" label="Taksit bulunmuyor."/>
                            </x-slot:empty>
                            @scope('cell_sale.unique_id', $taksit)
                            {{ $taksit->sale->unique_id ?? '-' }}
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
                        <x-table :headers="$headersServices" :rows="$sale->clientServices" wire:model="expandedService"
                                 striped
                                 expandable>
                            <x-slot:empty>
                                <x-icon name="o-cube" label="Hizmet bulunmuyor."/>
                            </x-slot:empty>
                            @scope('cell_sale.unique_id', $service)
                            {{ $service->sale->unique_id ?? 'Yok' }}
                            @endscope
                            @scope('cell_package.name', $service)
                            {{ $service->package->name ?? 'Yok' }}
                            @endscope
                            @scope('expansion', $service)
                            Personel : {{ $service->userServices->name ?? '' }}
                            <div class="bg-base-200 p-8 font-bold">
                                @foreach ($service->clientServiceUses as $uses)
                                    <x-list-item :item="$uses" no-separator no-hover>
                                        <x-slot:avatar>
                                            <x-badge value="{{ $uses->seans }} seans" class="badge-primary"/>
                                        </x-slot:avatar>
                                        <x-slot:value>
                                            {{ $uses->date_human_created }} - {{ $uses->message }}
                                        </x-slot:value>
                                        <x-slot:sub-value>
                                            {{ $uses->user->name ?? '' }}
                                        </x-slot:sub-value>
                                    </x-list-item>
                                @endforeach
                            </div>
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
                    @scope('actions', $sale)
                    <x-button icon="tabler.settings" wire:click="showDrawer({{ $sale->id }})"
                              class="btn-circle btn-sm btn-primary"/>
                    @endscope
                </x-table>
            </x-card>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($sales as $sale)
                        <x-card title="{{ $sale->sale_no }} - {{ $sale->date }}" separator class="mb-2">
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
                                    {{ $sale->unique_id }}
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
            <livewire:components.drawers.sale_drawer wire:model="drawerModel" lazy/>
        </div>
