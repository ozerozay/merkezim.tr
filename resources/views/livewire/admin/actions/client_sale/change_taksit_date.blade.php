<?php

use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Rule('required')]
    public $t_id;

    #[Rule('required')]
    public $taksit_date;

    public $config_taksit_date;

    public bool $show = false;

    public $label = '';

    public function showForm()
    {
        $this->show = true;
        $this->success($this->t_id);
    }

    public function mount()
    {
        $this->label = $this->taksit_date;
        $this->taksit_date = Carbon::createFromFormat('d/m/Y', $this->taksit_date)->format('Y-m-d');
    }

    public function save()
    {
        dump($this->validate());
        $this->dispatch('change-taksit-date', $this->validate());
        $this->label = Carbon::parse($this->taksit_date)->format('d/m/Y');
        $this->show = false;
    }
};
?>
<div>
<x-button :label="$label" wire:click="showForm" class="btn-ghost" />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Tarih Değiştir">
            <x-form wire:submit="save">
                <hr class="mb-5" />
                <x-datepicker label="Tarih" wire:model="taksit_date" icon="o-calendar"
                    :config="$config_taksit_date" />
                <x-slot:actions>
                    <x-button label="Değiştir" icon="o-check" type="submit" spinner="save"
                        class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>
