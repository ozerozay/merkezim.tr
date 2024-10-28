<?php

use App\Actions\Client\GetClientSales;
use App\Models\Sale;
use App\SaleStatus;
use App\Traits\LiveHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
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
        ];
    }

    public function placeholder()
    {
        return view('livewire.components.card.loading.loading');
    }

    public function changeView()
    {
        $this->view = $this->view == 'table' ? 'list' : 'table';
    }

    public bool $showDrawer1 = false;

    public function showDrawer($sale)
    {
        $this->showDrawer1 = true;
    }

    public string $group = 'group1';
};

?>
<div>
    <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <x-stat title="Toplam Tutar" value="{{ LiveHelper::price_text($total_active_taksit_remaining) }}" icon="o-credit-card" />
        <x-stat title="Kalan Ödeme" value="{{ LiveHelper::price_text($total_active_taksit_remaining) }}" icon="o-credit-card" />
        <x-stat title="Yapılan Ödeme" value="{{ LiveHelper::price_text($total_paid) }}" icon="o-credit-card" />
        <x-stat title="Gecikmiş Ödeme" value="{{ LiveHelper::price_text($total_active_late_payment) }}" icon="o-credit-card" />
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mt-5">
        <x-stat title="Toplam Seans" :value="$total_service" icon="tabler.info-circle" />
        <x-stat title="Kullanılan Seans" :value="($total_service - $total_service_remaining)" icon="tabler.info-circle" />
        <x-stat title="Kalan Seans" :value="$total_service_remaining" class="text-red-500"
            icon="tabler.info-circle" />
    </div>
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
            icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline" />

    </div>
    @if ($view == 'table')
    <div>
        <x-card>
            <x-table :headers="$headers" :rows="$sales" :sort-by="$sortBy" striped with-pagination>
          
                @scope('cell_status', $sale)
                <x-badge :value="$sale->status->label()" class="badge-{{ $sale->status->color() }}" />
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
                    <x-icon name="o-cube" label="Satış bulunmuyor." />
                </x-slot:empty>
                @scope('actions', $sale)
                <x-button icon="tabler.settings" wire:click="showDrawer({{ $sale->id }})" class="btn-circle btn-sm btn-primary" />
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
                    <x-badge :value="$sale->status->label()" class="badge-{{ $sale->status->color() }}" />
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
    <x-drawer wire:model="showDrawer1" title="Satış İşlemleri" separator with-close-button right class="w-full lg:w-1/3">
    <x-accordion wire:model="group">
    <x-collapse name="group1">
        <x-slot:heading>Bilgilerini Düzenle</x-slot:heading>
        <x-slot:content>Hello 1</x-slot:content>
    </x-collapse>
    <x-collapse name="group2">
        <x-slot:heading>Durumunu Düzenle</x-slot:heading>
        <x-slot:content>Hello 1</x-slot:content>
    </x-collapse>
    <x-collapse name="group3">
        <x-slot:heading>Hizmetler</x-slot:heading>
        <x-slot:content>Hello 2</x-slot:content>
    </x-collapse>
    <x-collapse name="group4">
        <x-slot:heading>Taksitler</x-slot:heading>
        <x-slot:content>Hello 3</x-slot:content>
    </x-collapse>
    <x-collapse name="group5">
        <x-slot:heading>Tahsilat</x-slot:heading>
        <x-slot:content>Hello 3</x-slot:content>
    </x-collapse>
</x-accordion>
</x-drawer>
</div>