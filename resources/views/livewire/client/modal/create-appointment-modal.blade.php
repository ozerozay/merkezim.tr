<div>
    <div class="relative min-h-screen">
        <!-- Loading Indicator -->
        <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <div class="flex flex-col items-center gap-2">
                    <span class="loading loading-spinner loading-md text-primary"></span>
                    <span class="text-sm text-base-content/70">{{ __('client.loading_overlay_message') }}</span>
                </div>
            </div>
        </div>

        <x-card :title="__('client.page_appointment_create')" :subtitle="__('client.page_appointment_subtitle')" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>

            <div x-data="{ step: @entangle('step') }" class="w-full max-w-2xl mx-auto space-y-6">
                <!-- Step 1: Appointment Type -->
                <div x-show="step === 1" class="space-y-4">
                    <h3 class="text-lg font-medium text-center mb-6">{{ __('client.appointment_type_selection') }}</h3>
                    
                    <!-- Tek Randevu -->
                    <div @click="$wire.selectAppointmentType('date')" 
                         class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                        <div class="card-body p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span class="text-2xl">📅</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-lg">{{ __('client.appointment_type_date.title') }}</h4>
                                    <p class="text-sm text-base-content/70">{{ __('client.appointment_type_date.description') }}</p>
                                    <p class="text-xs text-base-content/50 mt-1">{{ __('client.appointment_type_date.example', ['date' => date('d/m/Y H:i')]) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarih Aralığı -->
                    <div @click="$wire.selectAppointmentType('range')" 
                         class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                        <div class="card-body p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-success/10 flex items-center justify-center">
                                    <span class="text-2xl">📆</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-lg">{{ __('client.appointment_type_range.title') }}</h4>
                                    <p class="text-sm text-base-content/70">{{ __('client.appointment_type_range.description') }}</p>
                                    <p class="text-xs text-base-content/50 mt-1">{{ __('client.appointment_type_range.example', [
                                        'start_date' => date('d/m/Y'),
                                        'end_date' => \Carbon\Carbon::now()->addDays(7)->format('d/m/Y')
                                    ]) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Çoklu Tarih -->
                    <div @click="$wire.selectAppointmentType('multi')" 
                         class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                        <div class="card-body p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-warning/10 flex items-center justify-center">
                                    <span class="text-2xl">🔢</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-lg">{{ __('client.appointment_type_multi.title') }}</h4>
                                    <p class="text-sm text-base-content/70">{{ __('client.appointment_type_multi.description') }}</p>
                                    <p class="text-xs text-base-content/50 mt-1">{{ __('client.appointment_type_multi.example', ['days' => 'Cuma, Cumartesi, haftaya Salı']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Branch Selection -->
                <div x-show="step === 2" class="space-y-4">
                    <h3 class="text-lg font-medium text-center mb-6">{{ __('client.branch_selection_title') }}</h3>
                    
                    @foreach($this->branches as $branch)
                        <div @click="$wire.selectBranch({{ $branch->id }})" 
                             class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                        <span class="text-2xl">🏢</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-lg">{{ $branch->name }}</h4>
                                        <p class="text-sm text-base-content/70">{{ __('client.branch_selection_description') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <x-button label="{{ __('client.navigation_back') }}" 
                             wire:click="backToType()"
                             class="btn-outline btn-block mt-4"
                             icon="tabler.arrow-left"/>
                </div>

                <!-- Step 3: Service Category Selection -->
                <div x-show="step === 3" class="space-y-4">
                    <h3 class="text-lg font-medium text-center mb-6">{{ __('client.service_category_selection_title') }}</h3>
                    
                    @foreach($this->serviceCategories as $category)
                        <div @click="$wire.selectCategory({{ $category->id }})" 
                             class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                        <span class="text-2xl">📑</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-lg">{{ $category->name }}</h4>
                                        <p class="text-sm text-base-content/70">{{ __('client.service_category_selection_description') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <x-button label="{{ __('client.navigation_back') }}" 
                             wire:click="backToBranch()"
                             class="btn-outline btn-block mt-4"
                             icon="tabler.arrow-left"/>
                </div>

                <!-- Step 4: Service Selection -->
                <div x-show="step === 4" class="space-y-4">
                    @if ($appointmentType == 'date')
                        <h3 class="text-lg font-medium text-center mb-6">{{ __('client.room_selection_title') }}</h3>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            @foreach($rooms as $room)
                                <label class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                                    <div class="card-body p-4">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="selectedRoom" value="{{ $room->id }}"
                                                   wire:model="selectedRoom" wire:key="rdo-{{ Str::random(10) }}"
                                                   class="radio radio-primary">
                                            <span class="font-medium">{{ $room->name }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-center mb-6">{{ __('client.service_selection_title') }}</h3>
                    <div class="space-y-3">
                        @foreach ($services as $service)
                            <label class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                                <div class="card-body p-4">
                                    <div class="flex items-center gap-3">
                                        <x-checkbox wire:model="selectedServices.{{ $service->id }}"
                                                   wire:key="cxc-{{ Str::random(10) }}"/>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium">{{ $service->service->name }}</span>
                                                <span class="badge badge-primary">
                                                    {{ __('client.service_remaining', ['remaining' => $service->remaining]) }}
                                                </span>
                                            </div>
                                            <span class="text-sm text-base-content/70">{{ $service->service->duration }} dk</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex flex-col gap-2 mt-6">
                        <x-button class="btn-primary" wire:click="selectService">
                            {{ __('client.navigation_next') }}
                        </x-button>
                        <x-button label="{{ __('client.navigation_back') }}" 
                                 wire:click="backToCategory()"
                                 class="btn-outline"
                                 icon="tabler.arrow-left"/>
                    </div>
                </div>

                <!-- Step 5: Date Selection -->
                <div x-show="step === 5" class="space-y-4">
                    <h3 class="text-lg font-medium text-center mb-6">{{ __('client.appointment_date_selection_title') }}</h3>

                    @if (!empty($available_appointments_range))
                        <div class="card bg-base-100 shadow-sm">
                            <div class="card-body p-4 space-y-4">
                                <x-select-group :options="$available_appointments_range"
                                              wire:model="selected_available_date"
                                              label="{{ __('client.appointment_date_selection_title') }}"/>
                                
                                <x-textarea label="{{ __('client.appointment_notes_label') }}" 
                                          wire:model="appointmentMessage"/>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 mt-6">
                            <x-button class="btn-primary" wire:click="createAppointmentRange">
                                {{ __('client.appointment_create_button') }}
                            </x-button>
                            <x-button label="{{ __('client.navigation_back') }}" 
                                     wire:click="backToService()"
                                     class="btn-outline"
                                     icon="tabler.arrow-left"/>
                        </div>
                    @else
                        <div class="card bg-base-100 shadow-sm">
                            <div class="card-body p-4 space-y-4">
                                @if ($appointmentType == 'date')
                                    <livewire:components.form.date_time
                                        label="{{ __('client.appointment_date_selection_title') }}"
                                        minDate="{{ date('Y-m-d') }}"
                                        wire:model="selectedDate"
                                        :enableTime="true"
                                        wire:key="xvk-{{Str::random()}}"/>
                                    
                                    <x-textarea label="{{ __('client.appointment_notes_label') }}" 
                                              wire:model="appointmentMessage"/>

                                    <div class="flex flex-col gap-2 mt-4">
                                        <x-button class="btn-primary" wire:click="createAppointmentManuel">
                                            {{ __('client.appointment_create_button') }}
                                        </x-button>
                                        <x-button label="{{ __('client.navigation_back') }}" 
                                                 wire:click="backToService()"
                                                 class="btn-outline"
                                                 icon="tabler.arrow-left"/>
                                    </div>

                                @elseif ($appointmentType == 'range')
                                    <livewire:components.form.date_time
                                        label="{{ __('client.appointment_date_selection_title') }}"
                                        minDate="{{ date('Y-m-d') }}"
                                        wire:model="selectedDate"
                                        mode="range"
                                        wire:key="xvk-{{Str::random()}}"/>

                                    <x-button class="btn-primary btn-block" wire:click="findAvaibleAppointmentsRange">
                                        {{ __('client.appointment_range_find_button') }}
                                    </x-button>

                                @elseif($appointmentType == 'multi')
                                    <livewire:components.form.date_time
                                        label="{{ __('client.appointment_date_selection_title') }}"
                                        minDate="{{ date('Y-m-d') }}"
                                        wire:model="selectedDate"
                                        mode="multiple"
                                        wire:key="xvk-{{Str::random()}}"/>

                                    <x-button class="btn-primary btn-block" wire:click="findAvaibleAppointmentsMulti">
                                        {{ __('client.appointment_range_find_button') }}
                                    </x-button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Step 6: Complete -->
                <div x-show="step === 6" class="text-center space-y-4">
                    <div class="w-16 h-16 mx-auto rounded-full bg-success/10 flex items-center justify-center">
                        <span class="text-3xl">✅</span>
                    </div>
                    <h3 class="text-lg font-medium">{{ __('client.appointment_complete_title') }}</h3>
                    <p class="text-base-content/70">{{ __('client.appointment_complete_message') }}</p>
                </div>
            </div>
        </x-card>
    </div>
</div>