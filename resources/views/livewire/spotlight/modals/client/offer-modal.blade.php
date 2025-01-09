<div>
    <div class="overflow-x-hidden">
        <x-card title="Teklif İşlemleri" separator progress-indicator>
            <x-slot:menu>
                <x-button wire:key="close-button-{{ Str::random(10) }}" 
                         icon="tabler.x" 
                         class="btn-sm btn-outline" 
                         wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>

            <!-- Teklif Detayları -->
            <div wire:key="offer-details-{{ Str::random(10) }}" class="card bg-base-200 mb-4">
                <div class="card-body p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <x-icon wire:key="gift-icon-{{ Str::random(10) }}" 
                                   name="o-gift" 
                                   class="w-4 h-4 text-primary" />
                            <span class="text-sm font-medium">Teklif #{{ $offer->unique_id }}</span>
                        </div>
                        <span class="badge badge-{{ $offer->status->color() }}">{{ $offer->status->label() }}</span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm opacity-70">Tutar</span>
                            <span class="text-sm font-medium">@price($offer->total_amount)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm opacity-70">Son Geçerlilik</span>
                            <span class="text-sm font-medium">{{ $offer->expire_date?->format('d.m.Y') ?? 'Süresiz' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <x-accordion wire:key="accordion-{{ Str::random(10) }}" 
                        wire:model="group" 
                        separator 
                        class="bg-base-200">
                <!-- Tarih Değiştir -->
                <x-collapse wire:key="date-collapse-{{ Str::random(10) }}" name="group1">
                    <x-slot:heading>
                        <x-icon wire:key="calendar-icon-{{ Str::random(10) }}" 
                               name="o-calendar" 
                               label="Tarih Değiştir"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:key="date-form-{{ Str::random(10) }}" 
                               wire:submit="updateDate">
                            <x-input wire:key="date-input-{{ Str::random(10) }}"
                                    type="date" 
                                    label="Son Geçerlilik Tarihi" 
                                    wire:model="expire_date"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
                            <x-slot:actions>
                                <x-button wire:key="update-button-{{ Str::random(10) }}"
                                         label="Güncelle" 
                                         class="btn-primary btn-block" 
                                         type="submit"
                                         spinner="updateDate" />
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>

                <!-- İptal Et -->
                <x-collapse wire:key="cancel-collapse-{{ Str::random(10) }}" name="group2">
                    <x-slot:heading>
                        <x-icon wire:key="x-circle-icon-{{ Str::random(10) }}"
                               name="o-x-circle" 
                               label="İptal Et"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:key="cancel-form-{{ Str::random(10) }}"
                               wire:submit="delete">
                            <x-alert wire:key="cancel-alert-{{ Str::random(10) }}"
                                    title="Dikkat!"
                                    description="Teklifi iptal etmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
                                    class="alert-error mb-4" />
                            <x-textarea wire:key="cancel-reason-{{ Str::random(10) }}"
                                      label="İptal Nedeni" 
                                      wire:model="delete_reason"
                                      placeholder="Lütfen iptal nedenini açıklayın..." />
                            <x-slot:actions>
                                <x-button wire:key="cancel-button-{{ Str::random(10) }}"
                                         label="İptal Et" 
                                         class="btn-error btn-block" 
                                         type="submit"
                                         spinner="delete" />
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            </x-accordion>

            <!-- Teklifi Onayla -->
            <x-card wire:key="approve-card-{{ Str::random(10) }}"
                   title="Onayla" 
                   class="bg-base-200 mt-5">
                        <x-button wire:key="approve-button-{{ Str::random(10) }}"
                                 class="btn-primary btn-block" 
                                 label="Teklifi Onayla"
                                 wire:click="$dispatch('slide-over.open', {component: 'actions.finish-client-offer', arguments: {'offer': {{ $offer->id }}}})" />
            </x-card>
        </x-card>
    </div>
</div>
