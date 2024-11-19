<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')]
#[\Livewire\Attributes\Title('Seanslarım')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;
};

?>
@php
    $seans = [];
@endphp

<div>
    <x-header title="Seanslarım" separator progress-indicator>
        <x-slot:actions>
            <x-button class="btn-primary btn-sm" icon="o-plus">
                Seans Yükle
            </x-button>
        </x-slot:actions>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <x-card title="TÜM BACAK" separator class="mb-2 card w-full bg-base-100 cursor-pointer border">
            <x-slot:menu>
                <x-button icon="o-plus" class="btn-circle btn-outline btn-sm"/>
            </x-slot:menu>
            <x-progress value="12" max="100" class="progress-warning h-3"/>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kategori
                </x-slot:value>
                <x-slot:actions>
                    EPİLASYON
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kalan
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Toplam
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Son Tarih
                </x-slot:value>
                <x-slot:actions>
                    30/08/2018
                </x-slot:actions>
            </x-list-item>
        </x-card>
        <x-card title="TÜM BACAK" separator class="mb-2">
            <x-slot:menu>
                <x-button icon="o-plus" class="btn-circle btn-outline btn-sm"/>
            </x-slot:menu>
            <x-progress value="12" max="100" class="progress-warning h-3"/>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kategori
                </x-slot:value>
                <x-slot:actions>
                    EPİLASYON
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kalan
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Toplam
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Son Tarih
                </x-slot:value>
                <x-slot:actions>
                    30/08/2018
                </x-slot:actions>
            </x-list-item>
        </x-card>
        <x-card title="TÜM BACAK" separator class="mb-2">
            <x-slot:menu>
                <x-button icon="o-plus" class="btn-circle btn-outline btn-sm"/>
            </x-slot:menu>
            <x-progress value="12" max="100" class="progress-warning h-3"/>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kategori
                </x-slot:value>
                <x-slot:actions>
                    EPİLASYON
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kalan
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Toplam
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Son Tarih
                </x-slot:value>
                <x-slot:actions>
                    30/08/2018
                </x-slot:actions>
            </x-list-item>
        </x-card>
        <x-card title="TÜM BACAK" separator class="mb-2">
            <x-slot:menu>
                <x-button icon="o-plus" class="btn-circle btn-outline btn-sm"/>
            </x-slot:menu>
            <x-progress value="12" max="100" class="progress-warning h-3"/>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kategori
                </x-slot:value>
                <x-slot:actions>
                    EPİLASYON
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kalan
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Toplam
                </x-slot:value>
                <x-slot:actions>
                    5
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Son Tarih
                </x-slot:value>
                <x-slot:actions>
                    30/08/2018
                </x-slot:actions>
            </x-list-item>
        </x-card>
    </div>
</div>
