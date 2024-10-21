<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public $config_sale_date;

    public $pay_price = 0.0;

    public $pay_kasa_id = null;

    public $pay_date;

    public $kasa_collection;

    public bool $show = false;

    public function mount()
    {
        $this->config_sale_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];
        $this->config_sale_date['maxDate'] = Carbon::now()->format('Y-m-d');
        $this->pay_date = Carbon::now()->format('Y-m-d');
    }

    public function showForm()
    {
        $this->reset('pay_price', 'pay_kasa_id');
        $this->show = true;
    }

    #[On('card-cash-add-nakit-update-collection')]
    public function updateKasaCollection($kasa_collection)
    {
        $this->kasa_collection = collect($kasa_collection);
    }

    public function save()
    {
        $validator = Validator::make([
            'pay_price' => $this->pay_price,
            'pay_kasa_id' => $this->pay_kasa_id,
            'pay_date' => $this->pay_date,
        ], [
            'pay_price' => 'required|min:1|decimal:0,2',
            'pay_kasa_id' => 'required',
            'pay_date' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('card-cash-added', $validator->validated());
        $this->pay_price = 0;
        $this->pay_kasa_id = null;

    }
};
?>
<div>
    <x-button label="Peşinat Ekle" wire:click="showForm" icon="o-plus" class="btn-outline btn-sm" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Peşinat Ekle">
            <x-form wire:submit="save">
                <hr class="mb-5" />
                <x-datepicker label="Tarih" wire:model="pay_date" icon="o-calendar" :config="$config_sale_date" />
                <x-choices-offline label="Kasa" option-sub-label="branch.name" single wire:model="pay_kasa_id"
                    :options="$kasa_collection" />
                <x-input label="Tutar" wire:model="pay_price" suffix="₺" money />
                <x-slot:actions>
                    <x-button label="Ödeme Ekle" icon="o-credit-card" type="submit" spinner="save"
                        class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>