<div x-data="{ showHelp: false }">
    <x-slide-over title="Teklif Oluştur" 
                  subtitle="{{ $client->name ?? '' }}"
                  wire:key="slide-over-{{ Str::random(10) }}">
        
        <!-- Yardım Paneli -->
        <div x-show="showHelp" 
             x-transition
             wire:key="help-panel-{{ Str::random(10) }}"
             class="mb-4 bg-base-200 rounded-box p-4">
            <h3 class="font-medium mb-4">Yardım</h3>
            <div class="space-y-4 text-sm">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <x-icon name="o-currency-dollar" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <span class="font-medium block mb-1">Teklif Tutarı</span>
                        <p class="opacity-70">Teklif tutarı otomatik olarak seçilen hizmetlere göre hesaplanır. İsterseniz manuel olarak değiştirebilirsiniz.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <x-icon name="o-calendar" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <span class="font-medium block mb-1">Son Geçerlilik</span>
                        <p class="opacity-70">Son geçerlilik tarihi opsiyoneldir. Boş bırakırsanız teklif süresiz olarak geçerli olur.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <x-icon name="o-clock" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <span class="font-medium block mb-1">Kullanım Süresi</span>
                        <p class="opacity-70">Paket kullanım süresi 0 olarak seçilirse süresiz olur. Aksi halde seçilen ay kadar geçerli olur.</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <x-icon name="o-gift" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <span class="font-medium block mb-1">Hizmet ve Paketler</span>
                        <p class="opacity-70">Teklife istediğiniz kadar hizmet ve paket ekleyebilirsiniz. Hediye hizmetler toplam tutara dahil edilmez.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4" wire:key="form-content-{{ Str::random(10) }}">
            <!-- Fiyat ve Açıklama -->
            <div class="card bg-base-200">
                <div class="card-body p-4 space-y-4">
                    <x-input wire:key="price-input-{{ Str::random(10) }}"
                            label="Teklif Tutarı" 
                            wire:model="price" 
                            suffix="₺" 
                            money/>
                            
                    <x-textarea wire:key="message-input-{{ Str::random(10) }}"
                               label="Açıklama" 
                               wire:model="message"
                               placeholder="Teklif hakkında açıklama yazın..."/>
                </div>
            </div>

            <!-- Tarih ve Süre -->
            <div class="card bg-base-200">
                <div class="card-body p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <livewire:components.form.date_time
                            wire:key="date-field-{{ Str::random(10) }}"
                            label="Son Geçerlilik"
                            wire:model="expire_date"
                            minDate="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"/>

                        <livewire:components.form.number_dropdown
                            wire:key="month-field-{{ Str::random(10) }}"
                            label="Kullanım Süresi"
                            includeZero="true" 
                            suffix="Ay"
                            wire:model="month"/>
                    </div>
                </div>
            </div>

            <!-- Hizmet/Paket Ekleme -->
            <div class="card bg-base-200">
                <div class="card-body p-4">
                    <div class="grid grid-cols-2 gap-2">
                        <x-button wire:key="add-service-{{ Str::random(10) }}"
                                 class="btn-outline"
                                 icon="tabler.plus"
                                 wire:click="$dispatch('modal.open', {component: 'modals.select-service-modal', arguments: {'client': {{ $client->id }}}})">
                            Hizmet Ekle
                        </x-button>
                        
                        <x-button wire:key="add-package-{{ Str::random(10) }}"
                                 class="btn-outline"
                                 icon="tabler.plus"
                                 wire:click="$dispatch('modal.open', {component: 'modals.select-package-modal', arguments: {'client': {{ $client->id }}}})">
                            Paket Ekle
                        </x-button>
                    </div>

                    <!-- Seçili Hizmetler -->
                    @if($selected_services->isNotEmpty())
                        <div class="mt-4 space-y-2">
                            @foreach ($selected_services as $service)
                                <x-list-item wire:key="service-item-{{ $service['id'] }}-{{ Str::random(10) }}" 
                                           :item="$service" 
                                           no-separator 
                                           no-hover>
                                    @if ($service['gift'])
                                        <x-slot:avatar>
                                            <x-badge value="H" class="badge-primary"/>
                                        </x-slot:avatar>
                                    @endif
                                    <x-slot:value>{{ $service['name'] }}</x-slot:value>
                                    <x-slot:sub-value>{{ $service['quantity'] }} seans - @price($service['price'])</x-slot:sub-value>
                                    <x-slot:actions>
                                        <x-button wire:key="delete-service-{{ $service['id'] }}-{{ Str::random(10) }}"
                                                 icon="o-trash" 
                                                 class="text-red-500" 
                                                 wire:confirm="Emin misiniz?"
                                                 wire:click="deleteItem({{ $service['id'] }}, '{{ $service['type'] }}')" 
                                                 spinner/>
                                    </x-slot:actions>
                                </x-list-item>
                            @endforeach

                            <div class="grid grid-cols-2 gap-2 mt-4">
                                <x-button wire:key="total-price-{{ Str::random(10) }}" 
                                         class="btn-sm">
                                    Toplam:
                                    <x-badge class="badge-neutral">
                                        <x-slot:value>@price($this->totalPrice())</x-slot:value>
                                    </x-badge>
                                </x-button>
                                
                                <x-button wire:key="total-gift-{{ Str::random(10) }}" 
                                         class="btn-sm">
                                    Hediye:
                                    <x-badge class="badge-neutral">
                                        <x-slot:value>@price($this->totalGift())</x-slot:value>
                                    </x-badge>
                                </x-button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-slide-over>
</div>
