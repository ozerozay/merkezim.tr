<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $date;

    public $label;

    public $minDate;

    public $maxDate;

    public $config = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public function boot(){
        $this->config['minDate'] = $this->minDate;
        $this->config['maxDate'] = $this->maxDate;
    }
}; ?>

<div>
<x-datepicker :label="$label" wire:model="date" icon="o-calendar" :config="$config" />
</div>