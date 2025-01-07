<div class="relative text-base-content p-2 min-h-[200px]">
    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <span class="loading loading-spinner loading-md text-primary"></span>
                <span class="text-sm text-base-content/70">{{ __('client.loading') }}</span>
            </div>
        </div>
    </div>

    @if($serviceCategories->isNotEmpty())
        <div class="flex flex-col items-center justify-center p-8 text-center space-y-4">
            <div class="p-4 bg-primary/10 rounded-full">
                <span class="text-4xl">📦</span>
            </div>
            <div class="space-y-2">
                <h3 class="text-lg font-medium">{{ __('client.no_active_packages_title') }}</h3>
                <p class="text-base-content/70">{{ __('client.no_active_packages_description') }}</p>
            </div>
            <div class="flex gap-3 mt-4">
                <x-button link="{{ route('client.shop.packages') }}" 
                         class="btn-primary">
                    {{ __('client.view_packages_button') }}
                </x-button>
                <x-button link="{{ route('client.profil.reservation-request') }}" 
                         class="btn-outline">
                    {{ __('client.create_reservation_request_button') }}
                </x-button>
            </div>
        </div>
    @else
        <!-- Content -->
        <div class="h-full flex flex-col">
            <!-- Header Section -->
            <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary/10 rounded-xl">
                            <i class="text-2xl text-primary">📅</i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold">{{ __('client.page_appointment_create') }}</h2>
                            <p class="text-sm text-base-content/70">{{ __('client.page_appointment_subtitle') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sol Taraf: Adımlar -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card>
                        <!-- Progress Bar Section -->
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium">
                                    @if($step === 1)
                                        {{ __('client.service_category_selection_title') }}
                                    @elseif($step === 2)
                                        {{ __('client.service_selection_title') }}
                                    @elseif($step === 3)
                                        {{ __('client.appointment_date_selection_title') }}
                                    @else
                                        {{ __('client.appointment_available_slots_title') }}
                                    @endif
                                </h3>
                                <span class="text-sm text-base-content/70">
                                    {{ $step }}/{{ $totalSteps }}
                                </span>
                            </div>
                            
                            <progress class="progress progress-primary w-full" 
                                    value="{{ ($step / $totalSteps) * 100 }}" 
                                    max="100">
                            </progress>
                        </div>

                        <div x-data="{ step: @entangle('step') }">
                            <!-- Step 1: Hizmet Kategorisi Seçimi -->
                            <div x-show="step === 1" class="space-y-4">
                               
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($serviceCategories as $category)
                                            <div wire:key="category-{{ $category->id }}" 
                                                 wire:click="selectCategory({{ $category->id }}, '{{ $category->name }}')" 
                                                 class="card bg-base-100 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                                                <div class="card-body p-4">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                                            <span class="text-2xl">✂️</span>
                                                        </div>
                                                        <div class="flex-1">
                                                            <h4 class="font-medium text-lg">{{ $category->name }}</h4>
                                                            <p class="text-sm text-base-content/70">{{ __('client.service_category_selection_description') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>

                            <!-- Step 2: Servis Seçimi -->
                            <div x-show="step === 2" class="space-y-4">
                                <div class="space-y-3">
                                    @if($services->isNotEmpty())
                                        @foreach($services as $service)
                                            <label wire:key="service-{{ $service->id }}" 
                                                   class="block">
                                                <div class="bg-base-200/30 rounded-xl p-4 hover:shadow-md transition-all duration-300 cursor-pointer
                                                          {{ in_array($service->id, $selectedServices) ? 'ring-2 ring-primary' : '' }}">
                                                    <div class="flex items-center gap-4">
                                                        <div class="flex-none">
                                                            <x-checkbox wire:model="selectedServices" 
                                                                       value="{{ $service->id }}"
                                                                       class="checkbox-primary"/>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between gap-2">
                                                                <div>
                                                                    <h4 class="font-medium text-base">{{ $service->service->name }}</h4>
                                                                    <p class="text-sm text-base-content/70">
                                                                        <span class="text-xs">⏱</span>
                                                                        {{ $service->service->duration }} {{ __('client.service_duration_minutes') }}
                                                                    </p>
                                                                </div>
                                                                <span class="badge badge-primary whitespace-nowrap">
                                                                    {{ __('client.service_remaining', ['remaining' => $service->remaining]) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    @else
                                        <div class="text-center py-8 bg-base-200/30 rounded-xl">
                                            <div class="flex flex-col items-center gap-2">
                                                <span class="text-4xl">🔍</span>
                                                <p class="text-base-content/70">{{ __('client.error_no_services_found') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex justify-between mt-6">
                                    <x-button wire:click="$set('step', 1)" 
                                             class="btn-outline"
                                             icon="tabler.arrow-left">
                                        {{ __('client.navigation_back') }}
                                    </x-button>
                                    <x-button wire:click="goToDateSelection" 
                                             class="btn-primary"
                                             icon-right="tabler.arrow-right">
                                        {{ __('client.navigation_next') }}
                                    </x-button>
                                </div>
                            </div>

                            <!-- Step 3: Tarih Seçimi -->
                            <div x-show="step === 3" class="space-y-4">
                                <div class="bg-base-100 rounded-lg p-4">
                                    <div class="flex gap-4">
                                        <div class="flex-1">
                                            <livewire:components.form.date_time
                                                wire:key="date-picker-{{ Str::random(10) }}"
                                                label="{{ __('client.appointment_date_selection_title') }}"
                                                minDate="{{ date('Y-m-d') }}"
                                                wire:model="selectedDate"
                                                mode="range"/>
                                        </div>
                                        <div class="flex items-end">
                                            <x-button class="btn-primary" 
                                                     wire:click="findAvailableSlots">
                                                {{ __('client.appointment_range_find_button') }}
                                            </x-button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <x-button wire:click="$set('step', 2)" 
                                             class="btn-outline"
                                             icon="tabler.arrow-left">
                                        {{ __('client.navigation_back') }}
                                    </x-button>
                                </div>
                            </div>

                            <!-- Step 4: Uygun Saatler -->
                            <div x-show="step === 4" class="space-y-4">
                                <!-- Tarih Seçim Formu -->
                                <x-card>
                                    <livewire:components.form.date_time
                                        wire:key="date-picker-{{ Str::random(10) }}"
                                        label="{{ __('client.appointment_date_selection_title') }}"
                                        minDate="{{ date('Y-m-d') }}"
                                        wire:model="selectedDate"
                                        mode="range"/>

                                    <x-button class="btn-primary btn-block mt-4" 
                                             wire:click="findAvailableSlots">
                                        {{ __('client.appointment_range_find_button') }}
                                    </x-button>
                                </x-card>

                                @foreach($available_appointments_range as $date => $slots)
                                    <div class="collapse collapse-arrow bg-base-200/30 rounded-xl">
                                        <input type="checkbox" class="peer" @if($loop->first) checked @endif />
                                        <div class="collapse-title flex items-center gap-2">
                                            <div class="p-1.5 bg-primary/10 rounded-lg">
                                                <span class="text-primary">📅</span>
                                            </div>
                                            <h4 class="font-medium">{{ $date }}</h4>
                                        </div>
                                        <div class="collapse-content">
                                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 pt-4">
                                                @foreach($slots as $slot)
                                                    <label class="relative">
                                                        <input type="radio" 
                                                               wire:model.live="selected_available_date" 
                                                               value="{{ $slot['id'] }}"
                                                               x-on:change="$nextTick(() => $refs.appointmentNote.focus())"
                                                               class="peer sr-only">
                                                        <div class="p-2 text-center rounded-lg cursor-pointer 
                                                                  bg-base-100 hover:bg-primary/10 
                                                                  peer-checked:bg-primary peer-checked:text-primary-content
                                                                  transition-all duration-200">
                                                            <span class="text-sm">{{ $slot['name'] }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Randevu Notu -->
                                <div class="mt-6">
                                    <x-textarea wire:key="notes-{{ Str::random(10) }}"
                                                x-ref="appointmentNote"
                                                label="{{ __('client.appointment_notes_label') }}" 
                                                wire:model="appointmentMessage"
                                                placeholder="{{ __('client.appointment_notes_placeholder') }}"/>
                                </div>

                                <!-- Navigation -->
                                <div class="flex justify-between mt-6">
                                    <x-button wire:click="$set('step', 3)" 
                                             class="btn-outline"
                                             icon="tabler.arrow-left">
                                        {{ __('client.navigation_back') }}
                                    </x-button>
                                    <x-button wire:click="createAppointment" 
                                             class="btn-primary"
                                             icon-right="tabler.check">
                                        {{ __('client.appointment_create_button') }}
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Sağ Taraf: Randevu Özeti -->
                <div class="lg:col-span-1">
                    <x-card title="{{ __('client.appointment_summary_title') }}">
                        <div class="space-y-4">
                            <!-- Seçilen Kategori -->
                            @if($selectedCategory)
                                <div class="flex justify-between items-center">
                                    <span class="text-base-content/70">{{ __('client.appointment_summary_category') }}</span>
                                    <span class="font-medium">{{ $selectedCategoryName }}</span>
                                </div>
                            @endif

                            <!-- Seçilen Hizmetler -->
                            @if(!empty($selectedServicesDetails))
                                <div class="border-t pt-4">
                                    <h4 class="font-medium mb-2">{{ __('client.appointment_summary_services') }}</h4>
                                    <div class="space-y-2">
                                        @foreach($selectedServicesDetails as $index => $service)
                                            <div wire:key="selected-service-{{ $index }}" 
                                                 class="flex justify-between items-center">
                                                <span>{{ $service['name'] }}</span>
                                                <span class="text-sm text-base-content/70">{{ $service['duration'] }} dk</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Toplam Süre -->
                                <div class="border-t pt-4 flex justify-between items-center">
                                    <span class="font-medium">{{ __('client.appointment_summary_total_duration') }}</span>
                                    <span class="text-primary font-medium">{{ $totalDuration }} dk</span>
                                </div>
                            @endif

                            <!-- Seçilen Tarih -->
                            @if($selectedDate)
                                <div class="border-t pt-4 flex justify-between items-center">
                                    <span class="text-base-content/70">{{ __('client.appointment_summary_date') }}</span>
                                    <span class="font-medium">{{ $selectedDate }}</span>
                                </div>
                            @endif
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    @endif
</div>
