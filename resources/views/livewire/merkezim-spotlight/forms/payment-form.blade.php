<div class="p-3">
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-4">
            <!-- Başlık -->
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <x-icon name="o-banknotes" class="h-5 w-5" />
                </div>
                <div>
                    <h3 class="card-title text-base">Ödeme Yap</h3>
                    <p class="text-xs text-base-content/70">Danışan, müşteri veya masraf gruplarına ödeme yapın.</p>
                </div>
            </div>
            
            <form wire:submit.prevent="masrafSave" class="space-y-4">
                <!-- Tutar Input -->
                <div class="relative">
                    <x-input
                        wire:key="tutar-input-{{ Str::random(10) }}"
                        wire:model="price"
                        label="Tutar"
                        suffix="₺"
                        money />
                </div>

                <!-- Grid Container -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Tarih -->
                    <livewire:components.form.date_time 
                        wire:key="payment-date-{{ Str::random(10) }}"
                        label="Tarih" 
                        wire:model="payment_date" />

                    <!-- Kasa -->
                    <livewire:components.form.kasa_dropdown 
                        wire:key="kasa-dropdownn-{{ Str::random(10) }}"
                        wire:model="kasa_id" />
                </div>

                <!-- Masraf Grubu -->
                <livewire:components.form.masraf_dropdown 
                    wire:key="masraf-dropdown-{{ Str::random(10) }}"
                    wire:model="masraf_id" />

                <!-- Açıklama -->
                <x-textarea
                    wire:key="message-textarea-{{ Str::random(10) }}"
                    wire:model="message"
                    label="Açıklama"
                    placeholder="Ödeme detaylarını buraya yazın..."
                    icon="o-pencil"
                    rows="2"
                    size="sm" />

                <!-- Form Actions -->
                <div class="flex justify-end pt-3 border-t border-base-200">
                    <x-button
                        wire:key="save-button-{{ Str::random(10) }}"
                        type="submit"
                        label="Kaydet"
                        icon="o-check"
                        class="w-32 justify-center"
                        size="md"
                        spinner="masrafSave"
                        primary />
                </div>
            </form>
        </div>
    </div>
</div> 