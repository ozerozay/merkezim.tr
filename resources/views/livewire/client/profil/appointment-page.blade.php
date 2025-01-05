<div class="relative text-base-content p-2 min-h-[200px]">
    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <span class="loading loading-spinner loading-md text-primary"></span>
                <span class="text-sm text-base-content/70">Yükleniyor...</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="h-full flex flex-col">
        <!-- Header Section with Stats -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <i class="text-2xl text-primary">📅</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">{{ __('client.menu_appointment') }}</h2>
                        <p class="text-sm text-base-content/70">{{ __('client.page_appointment_subtitle') }}</p>
                    </div>
                </div>

                @if($create_appointment->isNotEmpty())
                    <x-button class="btn-primary" icon="o-plus"
                            wire:click="$dispatch('slide-over.open', { 'component': 'web.modal.create-appointment-modal' })">
                        {{ __('client.page_appointment_create') }}
                    </x-button>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-primary">
                        <i class="text-2xl">📊</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Toplam Randevu</div>
                    <div class="stat-value text-lg">{{ $data->count() }}</div>
                </div>
                
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-warning">
                        <i class="text-2xl">⏳</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Bekleyen</div>
                    <div class="stat-value text-lg">{{ $data->where('status', '!=', \App\AppointmentStatus::finish)->count() }}</div>
                </div>

                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-success">
                        <i class="text-2xl">✅</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Tamamlanan</div>
                    <div class="stat-value text-lg">{{ $data->where('status', \App\AppointmentStatus::finish)->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Active Appointments Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-warning/10 rounded-lg">
                    <i class="text-warning text-lg">⚡</i>
                </div>
                <h3 class="font-medium">Aktif Randevular</h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach ($data->where('status', '!=', \App\AppointmentStatus::finish)->all() as $appointment)
                    <div class="bg-base-200/30 rounded-xl p-4 hover:shadow-md transition-all duration-300 cursor-pointer"
                         wire:click="$dispatch('slide-over.open', {'component': 'web.modal.appointment-info-modal', 'arguments': {'appointment': '{{ $appointment->id }}'}})">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                        <span class="text-primary text-lg">📅</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $appointment->date->format('d/m/Y') }}</h4>
                                    <p class="text-xs opacity-50">{{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end gap-1">
                                <span class="badge badge-{{ $appointment->status->color() }} gap-1">
                                    {{ __("client.".$appointment->status->name) }}
                                </span>
                            </div>
                        </div>

                        @if ($show_services)
                            <div class="border-t border-base-300 pt-4">
                                <div class="text-sm font-medium mb-2">Hizmetler</div>
                                <div class="text-sm text-base-content/70">
                                    {{ $appointment->getServiceNamesPublic() }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Completed Appointments Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-success/10 rounded-lg">
                    <i class="text-success text-lg">✓</i>
                </div>
                <h3 class="font-medium">Tamamlanan Randevular</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($data->where('status', \App\AppointmentStatus::finish)->all() as $appointment)
                    <div class="bg-base-200/30 rounded-xl p-3 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-success/10 flex items-center justify-center">
                                    <span class="text-success text-sm">✓</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium">{{ $appointment->date->format('d/m/Y') }}</p>
                                    <p class="text-xs opacity-50">{{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}</p>
                                </div>
                            </div>
                            @if ($appointment->review)
                                <x-button :label="__('client.page_appointment_review_tip')" icon="tabler.star"
                                      wire:click="$dispatch('slide-over.open', {'component': 'web.modal.rate-appointment-modal', 'arguments': {'appointment': '{{ $appointment->id }}'}})"
                                      class="btn-success btn-sm"/>
                            @endif
                        </div>
                        @if ($show_services)
                            <div class="mt-2 text-sm text-base-content/70">
                                {{ $appointment->getFinishServiceNamesPublic() }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
