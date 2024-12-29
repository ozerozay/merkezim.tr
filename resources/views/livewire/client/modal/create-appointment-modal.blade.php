<div>
    <div class="relative min-h-screen">
        <div wire:loading>
            <!-- Overlay for this div -->
            <div class="absolute inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-10">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <!-- Loading Spinner -->
                    <div class="flex items-center justify-center">
                        <svg class="animate-spin h-16 w-16 text-black-500" xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </div>
                    <!-- Loading Text -->
                    <p class="text-black-500 text-xl font-semibold">{{ __('client.loading_overlay_message') }}</p>
                </div>
            </div>
        </div>
        <x-card :title="__('client.page_appointment_create')" :subtitle="__('client.page_appointment_subtitle')"
                separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
            <div x-data="{ step: @entangle('step') }" class="w-full max-w-2xl mx-auto space-y-2">
                <!-- Step Progress -->
                @if (1==2)
                    <div class="flex items-center justify-between">
                        <template
                            x-for="(title, index) in ['Randevu', 'Şube', 'Kategori', 'Hizmet', 'Tarih', 'Tamam']"
                            :key="index">
                            <div class="flex-1 flex flex-col items-center">
                                <div
                                    :class="{'bg-blue-600 text-white': step === index + 1, 'bg-gray-300 text-gray-700': step !== index + 1}"
                                    class="w-8 h-8 flex items-center justify-center rounded-full mb-2">
                                    <span x-text="index + 1"></span>
                                </div>
                                <p class="text-sm" x-text="title"></p>
                            </div>
                        </template>
                    </div>
                @endif

                <!-- Step 1: Appointment Type -->
                <div x-show="step === 1" class="space-y-2">
                    <label class="block cursor-pointer">
                        <div @click="$wire.selectAppointmentType('date')"
                             class="p-6 bg-indigo-50 hover:bg-indigo-100 border-2 border-indigo-300 rounded-xl shadow-md transform transition duration-300 hover:scale-105">
                            <div class="flex items-center space-x-4">
                                <span class="text-xl text-indigo-700">📅</span>
                                <div>
                                    <span
                                        class="text-lg font-semibold text-indigo-700">{{ __('client.appointment_type_date.title') }}</span>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('client.appointment_type_date.description') }}</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('client.appointment_type_date.example', ['date' => date('d/m/Y H:i')]) }}</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="block cursor-pointer">
                        <div @click="$wire.selectAppointmentType('range')"
                             class="p-6 bg-green-50 hover:bg-green-100 border-2 border-green-300 rounded-xl shadow-md transform transition duration-300 hover:scale-105">
                            <div class="flex items-center space-x-4">
                                <span class="text-xl text-green-700">📅</span>
                                <div>
                                    <span
                                        class="text-lg font-semibold text-green-700">{{ __('client.appointment_type_range.title') }}</span>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('client.appointment_type_range.description') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('client.appointment_type_range.example', [
                                        'start_date' => date('d/m/Y'),
                                        'end_date' => \Carbon\Carbon::now()->addDays(7)->format('d/m/Y')
                                    ]) }}</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="block cursor-pointer">
                        <div @click="$wire.selectAppointmentType('multi')"
                             class="p-6 bg-yellow-50 hover:bg-yellow-100 border-2 border-yellow-300 rounded-xl shadow-md transform transition duration-300 hover:scale-105">
                            <div class="flex items-center space-x-4">
                                <span class="text-xl text-yellow-700">🔢</span>
                                <div>
                                    <span
                                        class="text-lg font-semibold text-yellow-700">{{ __('client.appointment_type_multi.title') }}</span>
                                    <p class="mt-1 text-sm text-gray-500">{{ __('client.appointment_type_multi.description') }}</p>
                                    <p class="text-sm text-gray-500">{{ __('client.appointment_type_multi.example', ['days' => 'Cuma, Cumartesi, haftaya Salı']) }}</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Step 2: Branch Selection -->
                <div x-show="step === 2" class="space-y-6">
                    <h1 class="text-2xl font-semibold text-center">{{ __('client.branch_selection_title') }}</h1>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($this->branches as $branch)
                            <label class="block cursor-pointer">
                                <div @click="$wire.selectBranch({{ $branch->id }})"
                                     class="flex items-center p-4 border rounded-lg hover:shadow-md transition">
                                    <div class="flex items-start space-x-4">
                                        <div
                                            class="bg-indigo-500 text-white p-3 rounded-full flex justify-center items-center">
                                            <span class="text-xl">🏢</span>
                                        </div>
                                        <div>
                                            <span class="text-lg font-semibold block">
                                                {{ $branch->name }}
                                            </span>
                                            <span
                                                class="text-sm text-gray-500">{{ __('client.branch_selection_description') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <x-button label="{{ __('client.navigation_back') }}" wire:click="backToType()"
                                  class="btn-outline btn-block"
                                  icon="tabler.arrow-left"/>
                    </div>
                </div>

                <!-- Step 3: Service Category Selection -->
                <div x-show="step === 3" class="space-y-6">
                    <h1 class="text-2xl font-semibold text-center">{{ __('client.service_category_selection_title') }}</h1>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($this->serviceCategories as $category)
                            <label class="block cursor-pointer">
                                <div @click="$wire.selectCategory({{ $category->id }})"
                                     class="flex items-center p-4 border rounded-lg hover:shadow transition">
                                    <div class="bg-blue-500 text-white p-2 rounded-full">
                                        <x-icon name="tabler.category" class="h-6 w-6"></x-icon>
                                    </div>
                                    <div class="ml-4 flex flex-col">
                                        <span class="font-semibold text-lg">{{ $category->name }}</span>
                                        <span
                                            class="text-sm text-gray-500">{{ __('client.service_category_selection_description') }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                        <x-button label="{{ __('client.navigation_back') }}" wire:click="backToBranch()"
                                  class="btn-outline btn-block mt-3"
                                  icon="tabler.arrow-left"/>
                    </div>
                </div>

                <!-- Step 4: Service Selection -->
                <div x-show="step === 4" class="space-y-4">
                    @if ($appointmentType == 'date')
                        <h1 class="text-xl font-semibold text-center">{{ __('client.room_selection_title') }}</h1>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($rooms as $room)
                                <label class="block cursor-pointer">
                                    <div class="flex items-center p-3 border rounded-md hover:shadow-sm transition">
                                        <input type="radio"
                                               name="selectedRoom"
                                               value="{{ $room->id }}"
                                               wire:model="selectedRoom"
                                               wire:key="rdo-{{ Str::random(10) }}"
                                               class="form-radio text-theme ml-2">
                                        <span class="ml-4 text-sm font-medium">{{ $room->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <x-hr class="my-4"/>
                    @endif

                    <h1 class="text-xl font-semibold text-center">{{ __('client.service_selection_title') }}</h1>
                    <div class="space-y-2">
                        @foreach ($services as $service)
                            <label class="block cursor-pointer">
                                <div
                                    class="relative flex items-center p-3 border rounded-md hover:shadow-sm transition">
                                    <span
                                        class="absolute top-2 right-2 bg-blue-500 text-white text-xs py-0.5 px-1 rounded-full">
                                        {{ __('client.service_remaining', ['remaining' => $service->remaining]) }}
                                    </span>
                                    <x-checkbox
                                        wire:model="selectedServices.{{ $service->id }}"
                                        wire:key="cxc-{{ Str::random(10) }}"
                                        class="ml-2"
                                    />
                                    <div class="ml-4 text-sm">
                                        <span class="font-medium">{{ $service->service->name }}</span>
                                        <span class="text-gray-500">- {{ $service->service->duration }} dk</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <x-button class="btn-primary w-full"
                                  wire:click="selectService">{{ __('client.navigation_next') }}</x-button>
                        <x-button label="{{ __('client.navigation_back') }}" wire:click="backToCategory()"
                                  class="btn-outline btn-block mt-3"
                                  icon="tabler.arrow-left"/>
                    </div>
                </div>

                <!-- Step 5: Appointment Date Selection -->
                <div x-show="step === 5" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">{{ __('client.appointment_date_selection_title') }}</h1>

                    @if (!empty($available_appointments_range))
                        <x-select-group :options="$available_appointments_range"
                                        wire:model="selected_available_date"
                                        label="{{ __('client.appointment_date_selection_title') }}"/>
                        <x-textarea label="{{ __('client.appointment_notes_label') }}" wire:model="appointmentMessage"/>
                        <x-button class="btn-primary btn-block mt-2" wire:click="createAppointmentRange">
                            {{ __('client.appointment_create_button') }}
                        </x-button>
                        <x-button label="{{ __('client.navigation_back') }}" wire:click="backToService()"
                                  class="btn-outline btn-block mt-3"
                                  icon="tabler.arrow-left"/>
                    @else
                        <div class="p-3">
                            @if ($appointmentType == 'date')
                                <livewire:components.form.date_time
                                    label="{{ __('client.appointment_date_selection_title') }}"
                                    minDate="{{ date('Y-m-d') }}"
                                    wire:model="selectedDate"
                                    :enableTime="true"
                                    wire:key="xvk-{{Str::random()}}"/>
                                <x-textarea label="{{ __('client.appointment_notes_label') }}" class="mt-2"
                                            wire:model="appointmentMessage"/>
                                <x-button class="btn-primary btn-block mt-2" wire:click="createAppointmentManuel">
                                    {{ __('client.appointment_create_button') }}
                                </x-button>
                                <x-button label="{{ __('client.navigation_back') }}" wire:click="backToService()"
                                          class="btn-outline btn-block mt-3"
                                          icon="tabler.arrow-left"/>
                            @elseif ($appointmentType == 'range')
                                <livewire:components.form.date_time
                                    label="{{ __('client.appointment_date_selection_title') }}"
                                    minDate="{{ date('Y-m-d') }}"
                                    wire:model="selectedDate"
                                    mode="range"
                                    wire:key="xvk-{{Str::random()}}"/>
                                <x-button class="btn-primary btn-block mt-2" wire:click="findAvaibleAppointmentsRange">
                                    {{ __('client.appointment_range_find_button') }}
                                </x-button>
                            @elseif($appointmentType == 'multi')
                                <livewire:components.form.date_time
                                    label="{{ __('client.appointment_date_selection_title') }}"
                                    minDate="{{ date('Y-m-d') }}"
                                    wire:model="selectedDate"
                                    mode="multiple"
                                    wire:key="xvk-{{Str::random()}}"/>
                                <x-button class="btn-primary btn-block mt-2" wire:click="findAvaibleAppointmentsMulti">
                                    {{ __('client.appointment_range_find_button') }}
                                </x-button>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Step 6: Complete -->
                <div x-show="step === 6" class="space-y-4">
                    <h1 class="text-2xl font-semibold text-center">{{ __('client.appointment_complete_title') }}</h1>
                    <p class="text-center">{{ __('client.appointment_complete_message') }}</p>
                </div>
            </div>
        </x-card>
    </div>
</div>
