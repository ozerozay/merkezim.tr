<?php

use App\Actions\Client\GetClientByUniqueID;
use App\Actions\Kasa\GetKasaAction;
use App\Actions\User\UserBranchOrClientBranchAction;
use App\Models\Kasa;
use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public ?Collection $selected_cash;

    public $branch_ids = [];

    public $client = null;

    public $client_model = null;

    public $kasa_collection;

    public $maxPrice = 0;

    public function mount()
    {
        $this->init();
    }

    public function init()
    {
        LiveHelper::class;
        $this->client_model = GetClientByUniqueID::run(null, $this->client);
        $this->branch_ids = UserBranchOrClientBranchAction::run($this->client);

        $this->updateKasas();
    }

    public function updateKasas()
    {
        $this->kasa_collection = GetKasaAction::run($this->branch_ids);
        $this->dispatch('card-cash-add-nakit-update-collection', $this->kasa_collection)->to('components.card.cash.card_cash_add_cash');
    }

    #[Computed]
    public function totalPrice()
    {
        return $this->selected_cash->sum('price');
    }

    #[On('card-cash-added')]
    public function addNakit($info)
    {
        $toplamTutar = $this->maxPrice;
        $kalanTutar = $this->maxPrice - $this->totalPrice();

        if ($toplamTutar == 0) {
            $this->error('Hizmet ekleyin.');

            return;
        }

        if ($kalanTutar == 0) {
            $this->error('Tüm ödeme yapılandırıldı.');

            return;
        }

        if ($info['pay_price'] > $kalanTutar) {
            $info['pay_price'] = $kalanTutar;
            $this->warning('Tutar '.$info['pay_price'].' olarak düzeltildi.');
        }

        $kasa = Kasa::select('id', 'name')->where('id', $info['pay_kasa_id'])->first();

        $lastId = $this->selected_cash->last() != null ? $this->selected_cash->last()['id'] + 1 : 1;

        $this->selected_cash->push([
            'id' => $lastId,
            'date' => Carbon::createFromFormat('Y-m-d', $info['pay_date'])->format('d/m/Y'),
            'kasa' => $info['pay_kasa_id'],
            'kasa_name' => $kasa->name,
            'price' => $info['pay_price'],
        ]);

        $this->dispatchSelectedCash();

    }

    public function deleteItem($id)
    {
        $this->selected_cash = $this->selected_cash->reject(function ($item) use ($id) {
            return $item['id'] == $id;
        });
        $this->dispatchSelectedCash();
    }

    public function deleteCashes()
    {
        $this->selected_cash = collect();
        $this->dispatchSelectedCash();
    }

    public function dispatchSelectedCash()
    {
        $this->dispatch('card-cash-selected-cash-updated', $this->selected_cash);
    }

    #[On('card-cash-client-selected')]
    public function dispatchClientSelected($client)
    {
        $this->client = $client;
        $this->client_model = null;
        $this->init();
    }

    #[On('card-cash-max-price-changed')]
    public function changeMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
        $this->deleteCashes();
    }
};

?>
<div>
    <x-card title="Peşinat" separator progress-indicator>
        @foreach ($selected_cash as $cash)
        <x-list-item :item="$cash" no-separator no-hover>
            <x-slot:value>
                {{ $cash['kasa_name'] }}
            </x-slot:value>
            <x-slot:sub-value>
                {{ $cash['date'] }} - {{ LiveHelper::price_text($cash['price']) }}
            </x-slot:sub-value>
            <x-slot:actions>
                <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                    wire:click="deleteItem({{ $cash['id'] }})" spinner />
            </x-slot:actions>
        </x-list-item>
        @endforeach
        <x:slot:menu>
            <livewire:components.card.cash.card_cash_add_cash :kasa_collection="$kasa_collection" />
            @if ($selected_cash->count() > 0)
            <x-button icon="o-trash" class="text-red-500 btn-sm" wire:confirm="Emin misiniz ?"
                wire:click="deleteCashes({{ $cash['id'] }})" spinner />
            @endif
        </x:slot:menu>
        <x:slot:actions>
        Toplam : {{ LiveHelper::price_text($this->totalPrice()) }}
        </x:slot:actions>
    </x-card>
</div>