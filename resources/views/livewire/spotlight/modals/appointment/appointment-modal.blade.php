<div class="overflow-x-hidden" x-data="{ activeTab: 'info', showHelp: false }">
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
                <div class="flex items-center gap-2">
                    <button class="btn btn-sm btn-ghost" @click="showHelp = !showHelp">
                        <x-icon name="o-question-mark-circle" class="w-5 h-5" />
                    </button>
                    <button class="btn btn-sm btn-ghost" wire:click="$dispatch('slide-over.close')">
                        <x-icon name="o-x-mark" class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Yardım Paneli -->
            <div x-show="showHelp" 
                 x-transition
                 class="mt-4 bg-base-200 rounded-box p-4">
                <h3 class="font-medium mb-4">Yardım</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <x-icon name="o-information-circle" class="w-5 h-5 text-primary" />
                        </div>
                        <div>
                            <span class="font-medium block mb-1">Bilgi Sekmesi</span>
                            <p class="opacity-70">Randevu ve müşteri detaylarını, borç bilgilerini görüntüleyebilirsiniz.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <x-icon name="o-gift" class="w-5 h-5 text-primary" />
                        </div>
                        <div>
                            <span class="font-medium block mb-1">Teklifler Sekmesi</span>
                            <p class="opacity-70">Aktif teklifleri görüntüleyebilir ve yeni teklif oluşturabilirsiniz.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <x-icon name="o-check" class="w-5 h-5 text-success" />
                        </div>
                        <div>
                            <span class="font-medium block mb-1">Tamamla Sekmesi</span>
                            <p class="opacity-70">Randevuyu tamamlamak için hizmetleri ve personeli seçip, açıklama ekleyebilirsiniz.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <x-icon name="o-check-circle" class="w-5 h-5 text-info" />
                        </div>
                        <div>
                            <span class="font-medium block mb-1">Merkezde Sekmesi</span>
                            <p class="opacity-70">Müşterinin merkeze geldiğini belirtmek için kullanılır.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <x-icon name="o-arrow-path" class="w-5 h-5 text-warning" />
                        </div>
                        <div>
                            <span class="font-medium block mb-1">Yönlendir Sekmesi</span>
                            <p class="opacity-70">Randevuyu başka bir personele yönlendirmek için kullanılır.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <x-icon name="o-x-circle" class="w-5 h-5 text-error" />
                        </div>
                        <div>
                            <span class="font-medium block mb-1">İptal Sekmesi</span>
                            <p class="opacity-70">Randevuyu iptal etmek için kullanılır. İptal edildiğinde seanslar iade edilir.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <x-icon name="o-clock" class="w-5 h-5 text-neutral" />
                        </div>
                        <div>
                            <span class="font-medium block mb-1">Geçmiş Sekmesi</span>
                            <p class="opacity-70">Randevu ile ilgili tüm işlem geçmişini görüntüleyebilirsiniz.</p>
                        </div>
                    </div>
                </div>
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
            <button class="tab tooltip tooltip-bottom" 
                    data-tip="Teklifler"
                    :class="{ 'tab-active': activeTab === 'offers' }"
                    @click="activeTab = 'offers'">
                <x-icon name="o-gift" class="w-5 h-5" />
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
                </div>
            </div>
        </div>

        <!-- Teklifler Tab -->
        <div x-show="activeTab === 'offers'" class="space-y-4">
            <div class="card bg-base-200">
                <div class="card-body p-4">
                    @php
                        $activeOffers = $appointment->client->clientOffers()
                            ->where('status', App\OfferStatus::waiting)
                            ->where(function ($query) {
                                $query->whereNull('expire_date')
                                    ->orWhere('expire_date', '>=', date('Y-m-d'));
                            })
                            ->get();
                    @endphp
                    @if($activeOffers->isNotEmpty())
                        @foreach($activeOffers as $offer)
                            <div class="border-t border-base-300 pt-4 mt-4 first:border-t-0 first:pt-0 first:mt-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <x-icon name="o-gift" class="w-4 h-4 text-primary" />
                                        <span class="text-sm font-medium">Teklif #{{ $offer->unique_id }}</span>
                                    </div>
                                    <span class="badge badge-{{ $offer->status->color() }}">{{ $offer->status->label() }}</span>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm opacity-70">Teklif Tutarı</span>
                                        <span class="text-sm font-medium">@price($offer->price)</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm opacity-70">Son Geçerlilik</span>
                                        <span class="text-sm font-medium">{{ $offer->expire_date?->format('d.m.Y') ?? 'Süresiz' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm opacity-70">Oluşturan</span>
                                        <span class="text-sm font-medium">{{ $offer->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm opacity-70">Oluşturulma</span>
                                        <span class="text-sm font-medium">{{ $offer->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    
                                    @if($offer->items->isNotEmpty())
                                        <div class="border-t border-base-300 pt-2 mt-2">
                                            <p class="text-sm opacity-70 mb-1">Hizmetler</p>
                                            <div class="space-y-1">
                                                @foreach($offer->items as $item)
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm">{{ $item->itemable->name }}</span>
                                                        <span class="text-sm font-medium">{{ $item->quantity }} Seans</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <button wire:click="$dispatch('slide-over.open', {component: 'actions.finish-client-offer', arguments: {'offer': {{ $offer->id }}}})" 
                                            class="btn btn-primary btn-sm w-full mt-2">
                                        <x-icon name="o-check" class="w-4 h-4" />
                                        Teklifi Tamamla
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="flex justify-center mb-4">
                                <div class="p-3 bg-primary/10 rounded-full">
                                    <x-icon name="o-gift" class="w-8 h-8 text-primary" />
                                </div>
                            </div>
                            <h3 class="font-medium text-base mb-1">Aktif Teklif Bulunmuyor</h3>
                            <p class="text-sm opacity-70 mb-4">Bu müşteri için henüz aktif bir teklif oluşturulmamış.</p>
                            <button wire:click="$dispatch('slide-over.open', {component: 'actions.create-offer', arguments: {'client': {{ $appointment->client->id }}}})" 
                                    class="btn btn-primary btn-sm">
                                <x-icon name="o-plus" class="w-4 h-4" />
                                Yeni Teklif Oluştur
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tamamla Tab -->
        <div x-show="activeTab === 'finish'" class="space-y-4">
            <div class="card bg-base-200">
                <div class="card-body">
                    <x-form wire:submit="finish">
                        <livewire:components.client.client_service_appointment_dropdown
                             wire:key="services-{{ Str::random(10) }}"
                            :client_id="$appointment->client->id"
                            label="Hizmetler"
                            wire:model="appointmentClientServices" />

                        <div class="relative">
                            <livewire:components.form.staff_dropdown
                                wire:key="ssxaas-{{ Str::random(10) }}"
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
            </div>
        </div>

        <!-- Merkezde Tab -->
        <div x-show="activeTab === 'merkezde'" class="space-y-4">
            <div class="card bg-base-200">
                <div class="card-body">
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
            </div>
        </div>

        <!-- Yönlendir Tab -->
        <div x-show="activeTab === 'forward'" class="space-y-4">
            <div class="card bg-base-200">
                <div class="card-body">
                    <x-form wire:submit="forwardAppointment">
                        <div class="relative">
                            <livewire:components.form.staff_dropdown
                                 wire:key="ssxs-{{ Str::random(10) }}"
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
            </div>
        </div>

        <!-- İptal Tab -->
        <div x-show="activeTab === 'cancel'" class="space-y-4">
            <div class="card bg-base-200">
                <div class="card-body">
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
            </div>
        </div>

        <!-- Geçmiş Tab -->
        <div x-show="activeTab === 'history'" class="space-y-3">
            @forelse ($appointment->appointmentStatuses as $status)
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
            @empty
                <div class="text-center py-12">
                    <div class="flex justify-center mb-4">
                        <x-icon name="o-clock" class="w-12 h-12 text-base-300" />
                    </div>
                    <h3 class="font-medium text-base mb-1">Henüz İşlem Geçmişi Yok</h3>
                    <p class="text-sm opacity-70">Bu randevu için henüz herhangi bir durum güncellemesi yapılmamış.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
