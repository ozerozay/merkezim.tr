<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $show = false;

    public $config_taksit_date;

    public $first_date;

    public $price = 0.0;

    public $amount = 1;

    public function mount()
    {
        $this->config_taksit_date = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];
        $this->config_taksit_date['minDate'] = Carbon::now()->format('Y-m-d');
        $this->first_date = Carbon::now();
    }

    public function addTaksit()
    {
        $validator = Validator::make([
            'first_date' => $this->first_date,
            'price' => $this->price,
            'amount' => $this->amount,
        ], [
            'first_date' => 'required|after:today',
            'price' => 'required|min:1|decimal:0,2',
            'amount' => 'required|min:1',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('card-taksit-added', $validator->validated());
        $this->price = 0.0;
        $this->amount = 1;
        $this->first_date = Carbon::now();
        $this->show = false;
    }
};

?>

<div>
<x-button label="Taksit" icon="o-plus" @click="$wire.show = true" responsive spinner class="btn-sm btn-outline" />
    <x-modal wire:model="show" title="Taksit Ekle">
        <x-form wire:submit="addTaksit">
        <x-datepicker label="Tarih" wire:model="first_date" icon="o-calendar" :config="$config_taksit_date" />
        <x-input label="Taksit Tutarı" wire:model="price" suffix="₺" money />
            <livewire:components.form.number_dropdown label="Adet" wire:model="amount" />
            <x-slot:actions>
                <x-button label="İptal" @click="$wire.show = false" />
                <x-button label="Ekle" type="submit" spinner="addTaksit" icon="o-paper-airplane"
                    class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>