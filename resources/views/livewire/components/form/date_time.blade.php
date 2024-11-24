<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $date;

    public $label;

    public $minDate;

    public $maxDate;

    public $config = ['altFormat' => 'd/m/Y', 'locale' => 'tr'];

    public $enableTime = false;

    public $mode = null;

    public $timeOnly = false;

    public function mount(): void
    {
        $this->config = \App\Peren::dateConfig(
            min: $this->minDate,
            max: $this->maxDate,
            enableTime: $this->enableTime,
            mode: $this->mode,
            timeOnly: $this->timeOnly);
    }
}; ?>
<div wire:key="datediv-{{ Str::random(10) }}">
    <x-datepicker
        wire:key="date-{{ Str::random(10) }}"
        :label="$label"
        wire:model="date"
        icon="o-calendar"
        :config="$config"/>
</div>


