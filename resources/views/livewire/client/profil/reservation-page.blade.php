<div>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary/10 rounded-xl">
                    <i class="text-2xl text-primary">📝</i>
                </div>
                <div>
                    <h2 class="text-lg font-bold">{{ __('client.reservation_request_title') }}</h2>
                    <p class="text-sm text-base-content/70">{{ __('client.reservation_request_subtitle') }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form wire:submit="createReservationRequest" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sol Taraf: Form Alanları -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Şube Seçimi -->
                    @if($showBranchSelect)
                        <x-card wire:key="branch-card-{{ Str::random() }}">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="p-1.5 bg-primary/10 rounded-lg">
                                    <span class="text-primary">🏢</span>
                                </div>
                                <h3 class="font-medium">{{ __('client.select_branch') }}</h3>
                            </div>

                            <x-select 
                                wire:key="branch-select-{{ Str::random() }}"
                                label="{{ __('client.select_branch') }}"
                                wire:model.live="selectedBranch"
                                option-value="id"
                                option-label="name"
                                :options="$branches"
                            />
                        </x-card>
                    @endif

                    @if($selectedBranch || auth()->check())
                        <!-- Hizmet Seçimi -->
                        <x-card wire:key="services-card-{{ Str::random() }}">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="p-1.5 bg-primary/10 rounded-lg">
                                    <span class="text-primary">✂️</span>
                                </div>
                                <h3 class="font-medium">{{ __('client.select_services') }}</h3>
                            </div>

                            <x-choices 
                                wire:key="services-choices-{{ Str::random() }}"
                                wire:model="selectedServices"
                                :options="$services"
                                option-label="name"
                                option-value="id"
                                option-description="description"
                                compact
                                :multiple="true"
                                :placeholder="__('client.select_services_placeholder')"
                                class="w-full"
                                search-placeholder="{{ __('client.search_services') }}"
                                no-result="{{ __('client.no_services_found') }}"
                            />
                        </x-card>

                        <!-- Tarih ve Zaman Tercihi -->
                        <x-card wire:key="datetime-card-{{ Str::random() }}">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="p-1.5 bg-primary/10 rounded-lg">
                                    <span class="text-primary">📅</span>
                                </div>
                                <h3 class="font-medium">{{ __('client.date_and_time_preferences') }}</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <livewire:components.form.date_time
                                        wire:key="date-picker-{{ Str::random() }}"
                                        label="{{ __('client.preferred_date') }}"
                                        minDate="{{ date('Y-m-d') }}"
                                        wire:model="selectedDate"/>
                                </div>
                                <div>
                                    <x-select 
                                        wire:key="time-select-{{ Str::random() }}"
                                        label="{{ __('client.preferred_time') }}"
                                        wire:model="preferredTime"
                                        option-value="id"
                                        option-label="name"
                                        :options="$timePreferences"
                                    />
                                </div>
                            </div>
                        </x-card>

                        <!-- Not -->
                        <x-card wire:key="notes-card-{{ Str::random() }}">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="p-1.5 bg-primary/10 rounded-lg">
                                    <span class="text-primary">📝</span>
                                </div>
                                <h3 class="font-medium">{{ __('client.additional_info') }}</h3>
                            </div>

                            @if($showPhoneInput)
                                <div class="mb-4">
                                    <x-input 
                                        wire:key="phone-input-{{ Str::random() }}"
                                        label="{{ __('client.phone_number') }}"
                                        wire:model="phone"
                                        placeholder="{{ __('client.phone_placeholder') }}"
                                    />
                                </div>
                            @endif

                            <x-textarea 
                                wire:key="note-textarea-{{ Str::random() }}"
                                wire:model="note"
                                placeholder="{{ __('client.reservation_note_placeholder') }}"
                            />
                        </x-card>
                    @endif
                </div>

                <!-- Sağ Taraf: Özet -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4">
                        <x-card title="{{ __('client.reservation_summary') }}">
                            <div class="space-y-4">
                                <!-- Seçilen Hizmetler -->
                                @if(!empty($selectedServices))
                                    <div>
                                        <h4 class="font-medium mb-2">{{ __('client.selected_services') }}</h4>
                                        <div class="space-y-2">
                                            @foreach($services->whereIn('id', $selectedServices) as $service)
                                                <div class="flex justify-between items-center">
                                                    <span>{{ $service->name }}</span>
                                                    <span class="text-sm text-base-content/70">
                                                        {{ $service->duration }} {{ __('client.minutes_short') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Seçilen Tarih -->
                                @if($selectedDate)
                                    <div class="border-t pt-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-base-content/70">{{ __('client.preferred_date') }}</span>
                                            <span class="font-medium">{{ $selectedDate }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Tercih Edilen Zaman -->
                                @if($preferredTime)
                                    <div class="border-t pt-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-base-content/70">{{ __('client.preferred_time') }}</span>
                                            <span class="font-medium">{{ $timePreferences[$preferredTime] }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Submit Button -->
                                <div class="border-t pt-4">
                                    <x-button type="submit" 
                                             class="btn-primary btn-block"
                                             icon-right="tabler.check">
                                        {{ __('client.submit_reservation_request') }}
                                    </x-button>
                                </div>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
