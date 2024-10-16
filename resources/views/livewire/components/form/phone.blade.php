<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public $phone;
};
?>
<div>
<x-input label="Telefon Numaranız" wire:model="phone" icon="o-phone" x-mask="9999999999" />
</div>