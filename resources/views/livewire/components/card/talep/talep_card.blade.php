<?php

use App\Models\Talep;

new class extends \Livewire\Volt\Component {

    public ?Talep $talep;

    public function showDrawer($id): void
    {
        $this->dispatch('talep-show-drawer', $id);
    }

};

?>
<div>
    <x-card subtitle="{{ $talep->status->label() }} - {{ $talep->branch->name  }}" separator>
        <x-slot:title>
            <div class="flex flex-auto items-center">
                <x-badge class="badge-{{ $talep->status->color() }}"/>
                <p class="ml-2">{{ $talep->name }}</p>
            </div>
        </x-slot:title>
        <x-slot:menu>
            <x-button icon="tabler.settings" wire:click="showDrawer({{ $talep->id }})"
                      class="btn-outline btn-sm"
                      responsive/>
        </x-slot:menu>
        @if ($talep->status == \App\TalepStatus::randevu || $talep->status == \App\TalepStatus::sonra)
            <x-list-item :item="$talep">
                <x-slot:value>
                    İŞLEM TARİHİ
                </x-slot:value>
                <x-slot:actions>
                    {{ $talep->latestAction->date->format('d/m/Y H:i:s') }}
                </x-slot:actions>
            </x-list-item>
        @endif
        <x-list-item :item="$talep">
            <x-slot:value>
                TARİH
            </x-slot:value>
            <x-slot:actions>
                {{ $talep->dateHuman }}
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                Telefon
            </x-slot:value>
            <x-slot:actions>
                <x-button label="{{ $talep->phone }}" icon="o-phone" external link="tel:0{{ $talep->phone }}"/>
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                Telefon - Düzensiz
            </x-slot:value>
            <x-slot:actions>
                <x-button label="{{ $talep->phone_long }}" icon="o-phone" external link="tel:{{ $talep->phone_long }}"/>
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                Çeşit
            </x-slot:value>
            <x-slot:actions>
                {{ $talep->type->label() }}
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                İŞLEM
            </x-slot:value>
            <x-slot:actions>
                {{ $talep->talep_processes_count }}
            </x-slot:actions>
        </x-list-item>
        "{{ $talep->message  }}"
        <x-hr/>
        @if ($talep->latestAction)
            <p>
                {{ $talep->latestAction->status  }} - {{ $talep->latestAction->message }}
            </p>
            <p>{{ $talep->latestAction->user?->name ?? '' }}
                - {{ $talep->latestAction->date?->format('d/m/Y H:i:s') ?? 'TARİH YOK'  }}</p>
        @endif
    </x-card>
</div>
