<?php

use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public ?Collection $selected_taksits;

    public $maxPrice = 0;

    #[Computed]
    public function totalPrice() {}

    public function dispatchSelectedTaksits()
    {
        //LiveHelper::class;
        $this->dispatch('card-sale-taksit-selected-taksit-updated', $this->selected_taksits);
    }

    #[On('card-sale-taksit-max-price-changed')]
    public function changeMaxPrice($max)
    {
        $this->maxPrice = $max;
    }

    #[On('taksitlendir')]
    public function taksitlendir($info)
    {
        $kalan = $this->maxPrice;

        $taksit_sayisi = $info['taksit_sayi'];

        $taksit_para = $kalan / $taksit_sayisi;

        $taksit_tarih = $info['taksit_date'];

        $taksit_para_ondalik = number_format($taksit_para, 2, '.', '');

        for ($i = 0; $i < $taksit_sayisi; $i++) {
            $this->selected_taksits->push([
                'id' => $i,
                'price' => $taksit_para_ondalik,
                'date' => Carbon::parse($taksit_tarih)->addMonths($i)->format('d/m/Y'),
            ]);
        }

        $cikan_taksit = 0.0;

        foreach ($this->selected_taksits as $t) {
            $cikan_taksit += $t['price'];
        }

        $eklenecek = $kalan - $cikan_taksit;

        $lastItemKey = $this->selected_taksits->keys()->last();

        $this->selected_taksits = $this->selected_taksits->map(function ($item, $key) use ($lastItemKey, $eklenecek) {
            if ($key === $lastItemKey) {
                return [
                    'id' => $item['id'],
                    'price' => $item['price'] + $eklenecek,
                    'date' => $item['date'],
                ];  // Yeni değer
            }

            return $item;
        });

        $this->dispatchSelectedTaksits();
    }

    public function deleteTaksits()
    {
        $this->selected_taksits = collect();
        $this->dispatchSelectedTaksits();
    }
};

?>
<div>
    <x-card title="Taksit" separator progress-indicator>
        @foreach ($selected_taksits as $taksit)
        <x-list-item :item="$taksit" no-separator no-hover>
        <x-slot:avatar>
                {{ $taksit['id'] }}
            </x-slot:avatar>
            <x-slot:value>
            {{ LiveHelper::price_text($taksit['price']) }}
                
            </x-slot:value>
            <x-slot:sub-value>
            {{ $taksit['date'] }}
            </x-slot:sub-value>
            <x-slot:actions>
            <x-button icon="o-pencil" class="btn-outline btn-sm"
            wire:click="deleteTaksits()" spinner />
              
            </x-slot:actions>
        </x-list-item>
        @endforeach
        <x:slot:menu>
            @if ($selected_taksits->count() == 0)
            <livewire:components.card.sale_taksit.card_sale_taksit_add_taksit />
            @endif
            @if ($selected_taksits->count() > 0)
            <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
            wire:click="deleteTaksits()" spinner />
            @endif
        </x:slot:menu>
       
    </x-card>
</div>