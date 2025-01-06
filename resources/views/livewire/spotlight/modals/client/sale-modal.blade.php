<div class="overflow-x-hidden">
    <x-card title="Satış İşlemleri" separator progress-indicator>
        <x-slot:menu>
            <x-button 
                icon="tabler.x" 
                class="btn-sm btn-ghost" 
                wire:click="$dispatch('slide-over.close')"
            />
        </x-slot:menu>

        <!-- Satış Bilgileri -->
        <div class="mb-6 p-4 bg-base-200 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="space-y-1">
                    <h3 class="text-lg font-medium">{{ $sale->sale_no }}</h3>
                    <p class="text-sm text-base-content/60">#{{ $sale->unique_id }}</p>
                </div>
                <x-badge :value="$sale->status->label()" class="badge-{{ $sale->status->color() }}" />
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="space-y-1">
                    <p class="text-base-content/60">Tutar</p>
                    <p class="font-medium">@price($sale->price)</p>
                </div>
                <div class="space-y-1">
                    <p class="text-base-content/60">Kalan Taksit</p>
                    <p class="font-medium">@price($sale->total_taksit_remaining)</p>
                </div>
            </div>
        </div>

        <x-accordion wire:model="group" separator class="bg-base-200">
            <!-- Düzenleme Sekmesi -->
            <x-collapse name="group1">
                <x-slot:heading>
                    <div class="flex items-center gap-2">
                        <x-icon name="tabler.edit" class="w-4 h-4" />
                        <span>Düzenle</span>
                    </div>
                </x-slot:heading>
                
                <x-slot:content>
                    <x-form wire:submit="edit" class="space-y-4">
                        <livewire:components.form.date_time 
                            wire:key="date-xa-{{ Str::random(10) }}"
                            label="Satış Tarihi" 
                            wire:model="sale_date"
                        />

                        <livewire:components.form.date_time 
                            wire:key="date-sw-{{ Str::random(10) }}"
                            label="Son Kullanım Tarihi" 
                            wire:model="expire_date"
                        />

                        <livewire:components.form.staff_multi_dropdown 
                            wire:key="drawer-sale-mlti-{{Str::random(10)}}"
                            wire:model="sale_staffs"
                        />

                        <livewire:components.form.sale_type_dropdown 
                            wire:key="drawer-sale-type-{{Str::random(10)}}"
                            wire:model="sale_type"
                        />

                        <x-textarea 
                            label="Açıklama" 
                            wire:model="messageEdit"
                            placeholder="Düzenleme sebebini belirtin"
                        />

                        <x-slot:actions>
                            <x-button 
                                label="Kaydet" 
                                type="submit" 
                                spinner="edit" 
                                class="btn-primary w-full"
                            />
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>

            <!-- Durum Düzenleme Sekmesi -->
            <x-collapse name="group2">
                <x-slot:heading>
                    <div class="flex items-center gap-2">
                        <x-icon name="tabler.adjustments" class="w-4 h-4" />
                        <span>Durum Düzenle</span>
                    </div>
                </x-slot:heading>
                
                <x-slot:content>
                    <x-form wire:submit="changeStatus" class="space-y-4">
                        <livewire:components.form.active_cancel_status_dropdown
                            wire:key="xa-xx-{{ Str::random(10) }}"
                            wire:model.live="sale_status"
                            :expireFreeze="true"
                        />

                        <livewire:components.form.date_time 
                            wire:key="date-xx-{{ Str::random(10) }}"
                            label="Ne zaman açılsın ? - Dondurmak için doldurun."
                            wire:model="freeze_date"
                        />

                        <x-textarea 
                            label="Açıklama" 
                            wire:model="messageStatus"
                            placeholder="Durum değişikliği sebebini belirtin"
                        />

                        <x-slot:actions>
                            <x-button 
                                label="Kaydet" 
                                type="submit" 
                                spinner="changeStatus" 
                                class="btn-primary w-full"
                            />
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>

            <!-- Silme Sekmesi -->
            <x-collapse name="group3">
                <x-slot:heading>
                    <div class="flex items-center gap-2">
                        <x-icon name="tabler.trash" class="w-4 h-4 text-error" />
                        <span class="text-error">Sil</span>
                    </div>
                </x-slot:heading>
                
                <x-slot:content>
                    <x-form wire:submit="delete" class="space-y-4">
                        <x-alert 
                            title="Emin misiniz ?"
                            description="Satışı silmeniz durumunda ona bağlı kasa işlemleri, hizmetler ve taksitlerde silinir."
                            icon="tabler.alert-triangle" 
                            class="alert-error"
                        />

                        <x-textarea 
                            label="Açıklama" 
                            wire:model="messageDelete"
                            placeholder="Silme sebebini belirtin"
                        />

                        <x-slot:actions>
                            <x-button 
                                label="Sil" 
                                type="submit" 
                                class="btn-error w-full"
                                spinner="delete"
                            />
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>
        </x-accordion>
    </x-card>
</div>
