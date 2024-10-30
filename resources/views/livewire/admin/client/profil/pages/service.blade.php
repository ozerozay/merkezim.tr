<?php

use App\Actions\Client\GetClientServices;
use App\Models\ClientService;
use App\SaleStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithPagination, \App\Traits\WithViewPlaceHolder;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    public function mount(): void
    {
        $this->sortBy = ['column' => 'created_at', 'direction' => 'asc'];
    }

    public function getData()
    {
        return GetClientServices::run($this->client, true, $this->sortBy);
    }

    public function headers(): array
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
        $this->dispatch('drawer-service-update-id', $id)->to('components.drawers.drawer_service');
        $this->editing = true;
    }

    public function with(): array
    {
        return [
            'data' => $this->getData(),
            'headers' => $this->headers(),
            'statistic' => [
                ['name' => 'Toplam', 'value' => 0, 'number' => true],
                ['name' => 'Kalan', 'value' => 0, 'number' => true],
            ],
        ];
    }
};
?>
<div>
    <livewire:components.card.statistic.card_statistic :data="$statistic"/>
    <div class="flex justify-end mb-4 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
                  icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline"/>
    </div>
    @if ($view)
        <div>
            <x-card>
                <x-table :headers="$headers" :rows="$data" wire:model="expanded" :sort-by="$sortBy" striped
                         with-pagination
                         expandable>
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Hizmet bulunmuyor."/>
                    </x-slot:empty>
                    @can('service_process')
                        @scope('actions', $service)
                        <div class="flex">
                        </div>
                        @endscope
                    @endcan
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
                    @can('service_process')
                        @scope('actions', $service)
                        <x-button icon="tabler.settings"
                                  wire:click="showSettings({{ $service->id }})"
                                  class="btn-circle btn-sm btn-primary"/>
                        @endscope
                    @endcan
                </x-table>
            </x-card>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($data->count() == 0)
                <p class="text-center">Hizmet bulunmuyor.</p>
            @endif
            @foreach ($data as $service)
                <x-card title="{{ $service->service->name }}" separator class="mb-2">
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Durum
                        </x-slot:value>
                        <x-slot:actions>
                            <x-badge :value="$service->status->label()" class="badge-{{ $service->status->color() }}"/>
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Toplam
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $service->total }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Kalan
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $service->remaining }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Hediye
                        </x-slot:value>
                        <x-slot:actions>
                            @if ($service->gift)
                                <x-icon name="o-check" class="text-green-500"/>
                            @endif
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Tarih
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $service->date_human_created }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Satış
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $service->sale->unique_id ?? 'YOK' }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Paket
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $service->package->name ?? 'YOK' }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Açıklama
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $service->message }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$service">
                        <x-slot:value>
                            Personel
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $service->userServices->name ?? '' }}
                        </x-slot:actions>
                    </x-list-item>
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
                    <x:slot:menu>
                        <x-button icon="tabler.settings"
                                  wire:click="showSettings({{ $service->id }})"
                                  class="btn-circle btn-sm btn-primary"/>
                    </x:slot:menu>
                </x-card>
            @endforeach
        </div>
    @endif
    @can('service_process')
        <livewire:components.drawers.drawer_service wire:model="editing"/>
    @endcan
</div>
