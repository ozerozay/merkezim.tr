<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
class extends \Livewire\Volt\Component {

    public $type = 'oner';

    public $text = '';

    public $options = [
        [
            'id' => 'oner',
            'name' => 'Öneri'
        ],
        [
            'id' => 'sikayet',
            'name' => 'Şikayet'
        ],
        [
            'id' => 'randev',
            'name' => 'Randevu'
        ],
        [
            'id' => 'diger',
            'name' => 'Diğer'
        ]
    ];

};

?>
<div>
    <x-header title="Bize Ulaşın" separator progress-indicator>
        <x-slot:actions>
            <x-button icon="tabler.brand-instagram" label="@margeguzellik" external link="asd" class="btn-outline"
                      responsive/>
            <x-button icon="tabler.brand-whatsapp" label="0850 241 1010" external link="asd" class="btn-outline"
                      responsive/>
            <x-button icon="tabler.phone" label="0850 241 1010" external link="asd" class="btn-outline" responsive/>
        </x-slot:actions>
    </x-header>
    <x-card>
        <x-form wire:submit="test">
            <x-select :options="$options" label="Size nasıl yardımcı olabiliriz ?"/>
            <livewire:components.form.phone wire:model="phone"/>
            <x-input wire:model="eposta" label="E-posta Adresiniz"/>
            <x-textarea label="Mesajınız" wire:model="text" placeholder="Size nasıl yardımcı olabiliriz ?"/>
            <x-slot:actions>
                <x-button icon="tabler.send" label="Gönder" type="submit" class="btn-primary"/>
            </x-slot:actions>
        </x-form>
    </x-card>

</div>

