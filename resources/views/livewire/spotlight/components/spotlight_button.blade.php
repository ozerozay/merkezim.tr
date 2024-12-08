<?php

new class extends Livewire\Volt\Component {
    public function show()
    {
        $this->dispatch('spotlight.toggle');
    }
};

?>
<div class="fixed bottom-4 right-4">
    <x-button icon="tabler.wand" wire:click="$dispatch('spotlight.toggle')" class="btn-primary btn-circle btn-lg" />
</div>
