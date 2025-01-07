<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public $phone;

    public $label;
};
?>
<x-input 
    wire:key="phone-{{ Str::random(10) }}" 
    :label="$label ?? __('common.form.phone')" 
    wire:model="phone" 
    icon="o-phone"
    x-mask="9999999999"
    :placeholder="__('common.form.phone_placeholder')"
/>
