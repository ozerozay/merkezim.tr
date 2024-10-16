<?php

use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public $config_sale_date;

    #[Rule('required|min:0|decimal:0,2')]
    public $pay_price;

    #[Rule('required')]
    public $pay_kasa_id = null;

    #[Rule('required')]
    public $pay_date;

    public $branch;

    public bool $show = false;

    public function mount()
    {
        $this->pay_date = Carbon::now()->format('Y-m-d');
    }

    public function showForm()
    {
        $this->reset('pay_price', 'pay_kasa_id');
        $this->show = true;
    }

    public function save()
    {
        $this->dispatch('nakit-added', $this->validate());
    }
};
?>

<div>
    <x-button label="Peşinat Ekle" wire:click="showForm" icon="o-plus" class="btn-primary btn-sm" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Peşinat Ekle">
            <x-form wire:submit="save">
                <hr class="mb-5" />
                <x-datepicker label="Tarih" wire:model="pay_date" icon="o-calendar" :config="$config_sale_date" />
                <livewire:components.form.kasa_dropdown wire:model="pay_kasa_id" :branch="$branch" />
                <x-input label="Tutar" wire:model="pay_price" suffix="₺" money />
                <x-slot:actions>
                    <x-button label="Ödeme Ekle" icon="o-credit-card" type="submit" spinner="save"
                        class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>