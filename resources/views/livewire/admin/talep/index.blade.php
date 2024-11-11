<?php

new
#[\Livewire\Attributes\Title('Talep')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;
};

?>
<div>
    <x-header title="Talep" separator progress-indicator>
        <x-slot:actions>
            <x-dropdown label="Settings" class="btn-outline">
                <x-menu-item @click.stop="">
                    <x-checkbox wire:model="stat" label="asd"/>
                </x-menu-item>
                <x-menu-item>
                    <x-button icon="tabler.filter" class="btn-outline btn-sm" label="Filtrele"
                              wire:click="filterStatus"/>
                </x-menu-item>
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Filtrele" responsive/>
                </x-slot:trigger>
            </x-dropdown>
            <x-button icon="o-plus"
                      class="btn-primary"
                      label="Talep Oluştur"
                      responsive/>
        </x-slot:actions>
    </x-header>
</div>
