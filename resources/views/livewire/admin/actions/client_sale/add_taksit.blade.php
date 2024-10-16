<?php

use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Rule('required')]
    public $taksit_date;

    #[Rule('required')]
    public $taksit_sayi = 1;

    public $config_taksit_date;

    public bool $show = false;

    public function mount()
    {
        $this->taksit_date = Carbon::now()->format('Y-m-d');
    }

    public function showForm()
    {
        $this->reset('taksit_sayi');
        $this->show = true;
    }

    public function save()
    {
        $this->dispatch('taksitlendir', $this->validate());
        $this->show = false;

    }
};
?>
<div>
    <x-button label="Taksitlendir" wire:click="showForm" icon="o-plus" class="btn-primary btn-sm" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Taksitlendir">
            <x-form wire:submit="save">
                <hr class="mb-5" />
                <x-datepicker label="İlk Taksit Tarihi" wire:model="taksit_date" icon="o-calendar"
                    :config="$config_taksit_date" />
                <livewire:components.form.number_dropdown wire:model="taksit_sayi" label="Taksit Sayısı"
                    :branch="$taksit_sayi" :includeZero="false" :max="24" />
                <x-slot:actions>
                    <x-button label="Taksitlendir" icon="o-credit-card" type="submit" spinner="save"
                        class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>