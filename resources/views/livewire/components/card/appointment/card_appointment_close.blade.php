<?php

new class extends \Livewire\Volt\Component {
    public ?\App\Models\Appointment $appointment;

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
                <p class="ml-2">KAPALI</p>
            </div>
        </x-slot:title>
        <x-slot:menu>
            <x-button icon="tabler.settings" wire:click="showDrawer({{ $appointment->id }})"
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
        <p class="mt-3">"{{ $appointment->message ?? ''  }}"</p>
    </x-card>


</div>
