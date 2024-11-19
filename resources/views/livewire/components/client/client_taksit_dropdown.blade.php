<?php

use Illuminate\Support\Collection;

new class extends \Livewire\Volt\Component {

    #[\Livewire\Attributes\Modelable]
    public $taksit_id;

    public $client_id;

    public Collection $taksits;

    public $label = 'Aktif Taksitleri';

    public $status = \App\SaleStatus::success;


    public function mount(): void
    {
        $this->getTaksits($this->client_id, $this->status);
    }

    #[\Livewire\Attributes\On('reload-taksits')]
    public function reload_taksits($client): void
    {
        $this->getTaksits($client, $this->status);
    }

    public function getTaksits($client, $status = null): void
    {
        $this->taksits = \App\Actions\Client\GetClientTaksits::run($client, false, ['column' => 'date', 'direction' => 'asc'], null, $status, true);
    }
};

?>
<div>
    <x-choices-offline
        wire:model="taksit_id"
        :options="$taksits"
        option-sub-label="total"
        option-label="date"
        :label="$label"
        icon="o-magnifying-glass"
        no-result-text="Aktif taksiti bulunmuyor."
        single
        searchable>
        @scope('item', $taksit)
        <x-list-item :item="$taksit">
            <x-slot:value>
                {{ $taksit->dateHuman }} - {{ $taksit->sale->sale_no }}
            </x-slot:value>
            <x-slot:subValue>
                Kalan: @price($taksit->remaining) / @price($taksit->total)
            </x-slot:subValue>
            <x-slot:avatar>
                @if ($taksit->isLate)
                    <x-badge class="badge-error">asd</x-badge>
                @endif
            </x-slot:avatar>
        </x-list-item>
        @endscope
    </x-choices-offline>
    @php
        $toplamgecikmis = $this->taksits->sum(function($i){
            if ($i->isLate){
                return $i->remaining;
            }
            return 0;
        });
    @endphp
    <div class="grid grid-cols-2">
        <x-stat
            title="Gecikmiş"
            icon="tabler.cash-banknote"
            color="text-red-500">
            <x-slot:value>
                @price($toplamgecikmis)
            </x-slot:value>
        </x-stat>
        <x-stat
            title="Kalan Ödeme"
            icon="tabler.cash-banknote">
            <x-slot:value>
                @price($this->taksits->sum('remaining'))
            </x-slot:value>
        </x-stat>
    </div>
</div>
