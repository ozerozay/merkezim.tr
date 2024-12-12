<?php

use App\Actions\Client\GetClientTaksits;
use App\Traits\WithViewPlaceHolder;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use \Livewire\WithoutUrlPagination, Toast, WithPagination, WithViewPlaceHolder;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    protected $listeners = [
        'refresh-client-taksits' => '$refresh',
    ];

    public function getData()
    {
        return GetClientTaksits::run(client: $this->client, paginate: true, order: $this->sortBy);
    }

    public function headers(): array
    {
        return [['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'], ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'], ['key' => 'sale.unique_id', 'label' => 'Satış', 'sortBy' => 'sale_id'], ['key' => 'remaining', 'label' => 'Kalan', 'sortBy' => 'remaining'], ['key' => 'total', 'label' => 'Toplam', 'sortBy' => 'total'], ['key' => 'client_taksits_locks_count', 'label' => 'Kilit', 'sortable' => false]];
    }

    public function showSettings($id): void
    {
        $this->dispatch('drawer-taksit-update-id', $id)->to('components.drawers.drawer_taksit');
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
    <livewire:components.card.statistic.card_statistic :data="$statistic" />
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
            icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline" />
    </div>
    @if ($view)
        <div>
            <x-card>
                <x-table :headers="$headers" :rows="$data" :sort-by="$sortBy" striped with-pagination>
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Taksit bulunmuyor." />
                    </x-slot:empty>
                    @scope('cell_sale.unique_id', $taksit)
                        <p>{{ $taksit->sale->sale_no ?? '' }} - {{ $taksit->sale->unique_id ?? '' }}</p>
                    @endscope
                    @scope('cell_status', $taksit)
                        <x-badge :value="$taksit->status->label()" class="badge-{{ $taksit->status->color() }}" />
                    @endscope
                    @scope('cell_date', $taksit)
                        {{ $taksit->date_human }}
                    @endscope
                    @scope('cell_total', $taksit)
                        @price($taksit->total)
                    @endscope
                    @scope('cell_remaining', $taksit)
                        @price($taksit->remaining)
                    @endscope
                    @can('taksit_process')
                        @scope('actions', $taksit)
                            <x-button icon="tabler.settings"
                                wire:click="$dispatch('slide-over.open', {component: 'modals.client.taksit-modal', arguments : {'taksit' : {{ $taksit->id }}}})"
                                class="btn-circle btn-sm btn-primary" />
                        @endscope
                    @endcan
                </x-table>
            </x-card>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if ($data->count() == 0)
                <p class="text-center">Taksit bulunmuyor.</p>
            @endif
            @foreach ($data as $taksit)
                <x-card title="{{ $taksit->date_human }}" separator class="mb-2">
                    @can('taksit_process')
                        <x-slot:menu>
                            <x-button icon="tabler.settings"
                                wire:click="$dispatch('slide-over.open', {component: 'modals.client.taksit-modal', arguments : {'taksit' : {{ $taksit->id }}}})"
                                class="btn-circle btn-sm btn-primary" />
                        </x-slot:menu>
                    @endcan
                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Durum
                        </x-slot:value>
                        <x-slot:actions>
                            <x-badge :value="$taksit->status->label()" class="badge-{{ $taksit->status->color() }}" />
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Satış
                        </x-slot:value>
                        <x-slot:actions>
                            <p>{{ $taksit->sale->sale_no }} - {{ $taksit->sale->unique_id }}</p>
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Toplam
                        </x-slot:value>
                        <x-slot:actions>
                            @price($taksit->total)
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$taksit">
                        <x-slot:value>
                            Kalan
                        </x-slot:value>
                        <x-slot:actions>
                            @price($taksit->remaining)
                        </x-slot:actions>
                    </x-list-item>
                </x-card>
            @endforeach
        </div>
        <x-pagination :rows="$data" />
    @endif
</div>
