<div>
    <x-header title="{{ __('client.menu_appointment') }}" subtitle="{{ __('client.page_appointment_subtitle') }}"
              separator progress-indicator>
        @if($create_appointment->isNotEmpty())
            <x-slot:actions>
                <x-button class="btn-primary" icon="o-plus"
                          wire:click="$dispatch('slide-over.open', { 'component': 'web.modal.create-appointment-modal' })">
                    {{ __('client.page_appointment_create') }}
                </x-button>
            </x-slot:actions>
        @endif
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($data->where('status', '!=', \App\AppointmentStatus::finish)->all() as $appointment)
            <x-card shadow class="card w-full bg-base-100 cursor-pointer" wire:click="handleClick" separator>
                <x-slot:title class="text-lg font-black">
                    {{ $appointment->date->format('d/m/Y') }}
                </x-slot:title>
                <x-slot:menu>
                    {{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}
                </x-slot:menu>
                <div class="absolute top-0 right-0 -mt-4 -mr-1">
                    <span
                        class="badge badge-{{ $appointment->status->color() }} p-3 shadow-lg text-sm"> {{ $appointment->status->label() }} </span>
                </div>
                @if ($show_services)
                    {{ $appointment->service_names_public($appointment) }}
                @endif
            </x-card>
        @endforeach
    </div>
    <x-hr/>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($data->where('status', \App\AppointmentStatus::finish)->all() as $appointment)
            <x-card shadow class="card w-full bg-base-100" separator>
                {{-- TITLE --}}
                <x-slot:title class="text-lg font-black">
                    {{ $appointment->date->format('d/m/Y') }}
                </x-slot:title>

                {{-- MENU --}}
                <x-slot:menu>
                    {{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}
                </x-slot:menu>
                <div class="absolute top-0 right-0 -mt-4 -mr-1">
                    <span class="badge badge-{{ $appointment->status->color() }} p-3 shadow-lg text-sm">
                        {{ $appointment->status->label() }} </span>
                </div>
                @if ($show_services)
                    <div>{{ $appointment->finish_service_names_public($appointment) }}</div>
                    <x-hr/>
                @endif
                <div class="text-center">
                    <x-button label="Değerlendir & Bahşiş" icon="tabler.star"
                              wire:click="$dispatch('slide-over.open', {'component': 'web.modal.rate-appointment-modal', 'arguments': {'appointment': '{{ $appointment->id }}'}})"
                              class="btn-success btn-outline"/>
                </div>
            </x-card>
        @endforeach
    </div>
</div>
