<div class="overflow-x-hidden" x-data="{ activeTab: 'info' }">
    <!-- Header -->
    <div class="bg-base-100 sticky top-0 z-30 border-b border-base-200">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <div class="avatar placeholder">
                            <div class="w-8 h-8 rounded-full bg-primary/10">
                                <span class="text-primary">👤</span>
                            </div>
                        </div>
                        {{ $appointment->client->name }}
                    </h2>
                    <p class="text-sm opacity-70">{{ $appointment->date_start->format('d/m/Y H:i') }} - {{ $appointment->date_end->format('H:i') }}</p>
                </div>
                <button class="btn btn-sm btn-ghost" wire:click="$dispatch('slide-over.close')">
                    <x-icon name="o-x-mark" class="w-5 h-5" />
                </button>
            </div>

            <!-- Hızlı İletişim Butonları -->
            <div class="flex gap-2 mt-4">
                <a href="tel:+90{{ $appointment->client->phone }}" 
                   class="btn btn-sm flex-1 gap-2">
                    <x-icon name="o-phone" class="w-4 h-4" />
                    Ara
                </a>
                <button wire:click="$dispatch('slide-over.open', {component: 'actions.create-send-sms', arguments: {'client': {{ $appointment->client->id }}}})"
                        class="btn btn-sm flex-1 gap-2">
                    <x-icon name="o-envelope" class="w-4 h-4" />
                    SMS
                </button>
                <a href="whatsapp://phone=0{{ $appointment->client->phone }}"
                   class="btn btn-sm flex-1 gap-2">
                    <x-icon name="o-chat-bubble-left-right" class="w-4 h-4" />
                    Mesaj
                </a>
            </div>
        </div>

        <!-- Tab Menü -->
        <div class="tabs tabs-boxed bg-base-200 rounded-none px-4 flex justify-center">
            <button class="tab tooltip tooltip-bottom" 
                    data-tip="Bilgi"
                    :class="{ 'tab-active': activeTab === 'info' }"
                    @click="activeTab = 'info'">
                <x-icon name="o-information-circle" class="w-5 h-5" />
            </button>
            @if(in_array($appointment->status, $allowFinish))
                <button class="tab tooltip tooltip-bottom" 
                        data-tip="Tamamla"
                        :class="{ 'tab-active': activeTab === 'finish' }"
                        @click="activeTab = 'finish'">
                    <x-icon name="o-check" class="w-5 h-5" />
                </button>
            @endif
            @if(in_array($appointment->status, $allowMerkezde))
                <button class="tab tooltip tooltip-bottom" 
                        data-tip="Merkezde"
                        :class="{ 'tab-active': activeTab === 'merkezde' }"
                        @click="activeTab = 'merkezde'">
                    <x-icon name="o-check-circle" class="w-5 h-5" />
                </button>
            @endif
            @if(in_array($appointment->status, $allowForward))
                <button class="tab tooltip tooltip-bottom" 
                        data-tip="Yönlendir"
                        :class="{ 'tab-active': activeTab === 'forward' }"
                        @click="activeTab = 'forward'">
                    <x-icon name="o-arrow-path" class="w-5 h-5" />
                </button>
            @endif
            @if(!in_array($appointment->status, $allowCancel))
                <button class="tab tooltip tooltip-bottom" 
                        data-tip="İptal"
                        :class="{ 'tab-active': activeTab === 'cancel' }"
                        @click="activeTab = 'cancel'">
                    <x-icon name="o-x-circle" class="w-5 h-5" />
                </button>
            @endif
            <button class="tab tooltip tooltip-bottom" 
                    data-tip="Geçmiş"
                    :class="{ 'tab-active': activeTab === 'history' }"
                    @click="activeTab = 'history'">
                <x-icon name="o-clock" class="w-5 h-5" />
            </button>
        </div>
    </div>

    <!-- Tab İçerikleri -->
    <div class="p-4">
        <!-- Bilgi Tab -->
        <div x-show="activeTab === 'info'" class="space-y-4">
            <!-- Durum ve Müşteri Bilgileri -->
            <div class="card bg-base-200">
                <div class="card-body p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">Durum</span>
                        <span class="badge badge-{{ $appointment->status->color() }}">
                            {{ $appointment->status->label() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Telefon</span>
                        <a href="tel:+90{{ $appointment->client->phone }}" class="text-sm font-medium link">
                            +90{{ $appointment->client->phone }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Randevu Detayları -->
            <div class="card bg-base-200">
                <div class="card-body p-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Hizmetler</span>
                        <span class="text-sm font-medium">{{ $appointment->serviceNames }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Oda</span>
                        <span class="text-sm font-medium">{{ $appointment->serviceRoom->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Süre</span>
                        <span class="text-sm font-medium">{{ $appointment->duration }} DK</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Tarih</span>
                        <span class="text-sm font-medium">{{ $appointment->date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Saat</span>
                        <span class="text-sm font-medium">
                            {{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}
                        </span>
                    </div>
                    @if($appointment->message)
                        <div class="pt-2 border-t border-base-300">
                            <p class="text-sm opacity-70">{{ $appointment->message }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ödeme Bilgileri -->
            <div class="card bg-base-200">
                <div class="card-body p-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Toplam Borç</span>
                        <span class="text-sm font-medium">@price($appointment->client->totalDebt())</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm">Gecikmiş Ödeme</span>
                        <span class="text-sm font-medium text-error">@price($appointment->client->totalLateDebt())</span>
                    </div>
                    @if($appointment->hasDelayedPayment)
                        <div class="alert alert-error alert-sm">
                            <x-icon name="o-exclamation-triangle" class="w-4 h-4" />
                            <span class="text-sm">Gecikmiş Ödeme Mevcut</span>
                        </div>
                    @endif
                    @if($appointment->hasActiveOffer)
                        <div class="alert alert-info alert-sm">
                            <x-icon name="o-information-circle" class="w-4 h-4" />
                            <span class="text-sm">Aktif Teklif Mevcut</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tamamla Tab -->
        <div x-show="activeTab === 'finish'" class="space-y-4">
            <x-form wire:submit="finish">
                <livewire:components.client.client_service_appointment_dropdown
                    wire:key="finish-services-{{ $appointment->id }}"
                    :client_id="$appointment->client->id"
                    label="Hizmetler"
                    wire:model="appointmentClientServices" />

                <div class="relative">
                    <livewire:components.form.staff_dropdown
                        wire:key="finish-staff-{{ $appointment->id }}"
                        label="Personel"
                        wire:model="finishUser"
                        dropdownPosition="bottom" />
                </div>

                <x-textarea label="Açıklama" wire:model="messageApprove" />

                <x-button label="Tamamla" 
                         type="submit" 
                         class="btn-primary w-full" 
                         spinner="finish" />
            </x-form>
        </div>

        <!-- Merkezde Tab -->
        <div x-show="activeTab === 'merkezde'" class="space-y-4">
            <x-form wire:submit="confirmAppointment">
                <x-select label="Durum"
                         wire:model="merkezdeTeyitliStatus"
                         :options="$merkezdeTeyitliStatuses" />

                <x-textarea label="Açıklama" wire:model="messageMerkezde" />

                <x-button label="Kaydet" 
                         type="submit" 
                         class="btn-primary w-full" 
                         spinner="confirmAppointment" />
            </x-form>
        </div>

        <!-- Yönlendir Tab -->
        <div x-show="activeTab === 'forward'" class="space-y-4">
            <x-form wire:submit="forwardAppointment">
                <div class="relative">
                    <livewire:components.form.staff_dropdown
                        wire:key="forward-staff-{{ $appointment->id }}"
                        label="Personel"
                        wire:model="forwardUser"
                        dropdownPosition="bottom" />
                </div>

                <x-textarea label="Açıklama" wire:model="messageForward" />

                <x-button label="Yönlendir" 
                         type="submit" 
                         class="btn-primary w-full" 
                         spinner="forwardAppointment" />
            </x-form>
        </div>

        <!-- İptal Tab -->
        <div x-show="activeTab === 'cancel'" class="space-y-4">
            <x-form wire:submit="cancelAppointment">
                <x-alert title="Dikkat!"
                        description="İptal ettiğinizde seanslar geri yüklenir ve bu işlem geri alınamaz."
                        class="alert-error mb-4" />

                <x-textarea label="İptal Nedeni" wire:model="messageCancel" />

                <x-button label="İptal Et" 
                         type="submit" 
                         class="btn-error w-full" 
                         spinner="cancelAppointment" />
            </x-form>
        </div>

        <!-- Geçmiş Tab -->
        <div x-show="activeTab === 'history'" class="space-y-3">
            @foreach ($appointment->appointmentStatuses as $status)
                <div class="card bg-base-200">
                    <div class="card-body p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="badge badge-{{ $status->status->color() }} mb-1">
                                    {{ $status->status->label() }}
                                </span>
                                <p class="text-sm">{{ $status->message }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ $status->user->name }}</p>
                                <p class="text-xs opacity-70">{{ $status->dateHuman }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
