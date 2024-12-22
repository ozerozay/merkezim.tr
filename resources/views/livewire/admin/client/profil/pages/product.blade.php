<?php

use App\Traits\WithViewPlaceHolder;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends \Livewire\Volt\Component {
    use \Livewire\WithoutUrlPagination, Toast, WithPagination, WithViewPlaceHolder;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    protected $listeners = [
        'refresh-client-product-sales' => '$refresh',
    ];

    public function getData()
    {
        return \App\Actions\Client\GetClientProductSales::run(client: $this->client, paginate: true, order: $this->sortBy);
    }

    public function headers(): array
    {
        return [['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'], ['key' => 'unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'], ['key' => 'sale_product_items_count', 'label' => 'Ürün', 'sortable' => false], ['key' => 'price', 'label' => 'Tutar', 'sortBy' => 'price'], ['key' => 'message', 'label' => 'Açıklama', 'sortBy' => 'message']];
    }

    public function showSettings($id): void
    {
        $this->dispatch('drawer-sale-product-update-id', $id)->to('components.drawers.drawer_sale_product');
        $this->editing = true;
    }

    public function with(): array
    {
        return [
            'data' => $this->getData(),
            'headers' => $this->headers(),
            'statistic' => [['name' => 'Toplam', 'value' => 0, 'number' => true], ['name' => 'Kalan', 'value' => 0, 'number' => true], ['name' => 'Gecikmiş', 'value' => 0, 'number' => true, 'red' => true]],
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
                <x-table :headers="$headers" :rows="$data" :sort-by="$sortBy" striped with-pagination>
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Ürün satışı bulunmuyor."/>
                    </x-slot:empty>
                    @scope('cell_date', $taksit)
                    {{ $taksit->date_human }}
                    @endscope
                    @can('sale_product_process')
                        @scope('actions', $taksit)
                        <x-button icon="tabler.settings"
                                  wire:click="$dispatch('slide-over.open', {component: 'modals.client.sale-product-modal', arguments : {'product' : {{ $taksit->id }}}})"
                                  class="btn-circle btn-sm btn-primary"/>
                        @endscope
                    @endcan
                </x-table>
            </x-card>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if ($data->count() == 0)
                <p class="text-center">Ürün satışı bulunmuyor.</p>
            @endif
            @foreach ($data as $taksit)
                <x-card title="{{ $taksit->date_human }}" separator class="mb-2">
                    @can('sale_product_process')
                        <x-slot:menu>
                            <x-button icon="tabler.settings"
                                      wire:click="$dispatch('slide-over.open', {component: 'modals.client.sale-product-modal', arguments : {'product' : {{ $taksit->id }}}})"
                                      class="btn-circle btn-sm btn-primary"/>
                        </x-slot:menu>
                    @endcan

                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Satış
                        </x-slot:value>
                        <x-slot:actions>
                            <p>{{ $taksit->unique_id }}</p>
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Ürün Sayısı
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $taksit->sale_product_items_count }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Tutar
                        </x-slot:value>
                        <x-slot:actions>
                            @price($taksit->price)
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Açıklama
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $taksit->message }}
                        </x-slot:actions>
                    </x-list-item>
                </x-card>
            @endforeach
        </div>
    @endif
    @can('sale_product_processs')
        <livewire:components.drawers.drawer_sale_product wire:model="editing"/>
    @endcan
</div>
