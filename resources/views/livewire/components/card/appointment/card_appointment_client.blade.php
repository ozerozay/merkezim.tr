<?php

use App\Models\Appointment;

new class extends \Livewire\Volt\Component
{
    public ?Appointment $appointment;

    public function showDrawer($id): void
    {
        $this->dispatch('appointment-show-drawer', $id);
    }
};

?>
<div>
    <x-card separator>
        <x-slot:title>
            <div class="flex flex-auto items-center">
                <x-badge class="{{ $appointment->type->color() }}"/>
                <p class="ml-2">{{ $appointment->client->name }}</p>
            </div>
        </x-slot:title>
        <x-slot:menu>
            <x-button icon="tabler.settings" wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})"
                      class="btn-outline btn-sm"
                      responsive/>
        </x-slot:menu>
        <x-list-item :item="$appointment">
            <x-slot:value>
                SAAT
            </x-slot:value>
            <x-slot:actions>
                {{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i')  }}
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$appointment">
            <x-slot:value>
                SÜRE
            </x-slot:value>
            <x-slot:actions>
                {{ $appointment->duration }} DK
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$appointment">
            <x-slot:value>
                DURUM
            </x-slot:value>
            <x-slot:actions>
                <x-badge class="{{ $appointment->status->color()  }}" value="{{ $appointment->status->label() }}"/>
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$appointment">
            <x-slot:value>
                GECİKMİŞ ÖDEME
            </x-slot:value>
            <x-slot:actions>
                YAPILACAK
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$appointment">
            <x-slot:value>
                TELEFON
            </x-slot:value>
            <x-slot:actions>
                {{ $appointment->client->phone  }}
            </x-slot:actions>
        </x-list-item>
        <p class="mt-3">{{ $appointment->serviceNames  }}</p>
        <p class="mt-3">"{{ $appointment->message ?? ''  }}"</p>
    </x-card>


</div>
