<div>
    <div class="overflow-x-hidden">
        <x-card title="Ödeme Al" separator progress-indicator>
            <x-tabs wire:model="selectedTab">
                <x-tab name="masraf" label="Masraf">
                    <div>
                        <x-form wire:submit="masrafSave">
                            <livewire:components.form.date_time wire:key="date-fieeeeld-{{ Str::random(10) }}"
                                label="Tarih" wire:model="payment_date" />
                            <livewire:components.form.kasa_dropdown wire:model="kasa_id" />
                            <livewire:components.form.masraf_dropdown wire:model="masraf_id" />
                            <x-input label="Tutar" wire:model="price" suffix="₺" money />
                            <x-input label="Açıklama" wire:model="message" />
                            <x-slot:actions>
                                <x-button label="Gönder" icon="o-paper-airplane" type="submit" spinner="masrafSave"
                                    class="btn-primary" />
                            </x-slot:actions>
                        </x-form>
                    </div>
                </x-tab>
                <x-tab name="staff" label="Personel">
                    <div>
                        <x-form wire:submit="staffSave">
                            <livewire:components.form.date_time wire:key="date-fieeeeld-{{ Str::random(10) }}"
                                label="Tarih" wire:model="payment_date" />
                            <livewire:components.form.masraf_dropdown wire:model="masraf_id" />
                            <livewire:components.form.staff_dropdown wire:model="staff_id" />
                            <livewire:components.form.kasa_dropdown wire:model="kasa_id" />
                            <x-input label="Tutar" wire:model="price" suffix="₺" money />
                            <x-input label="Açıklama" wire:model="message" />
                            <x-slot:actions>
                                <x-button label="Gönder" icon="o-paper-airplane" type="submit" spinner="staffSave"
                                    class="btn-primary" />
                            </x-slot:actions>
                        </x-form>
                    </div>
                </x-tab>
                <x-tab name="client" label="Danışan">
                    <div>
                        <x-alert title="Danışandan ödeme almak için 'Tahsilat' sayfasını kullanın."
                            icon="o-exclamation-triangle" />
                        <x-form wire:submit="clientSave">
                            <livewire:components.form.date_time wire:key="date-fieeeeld-{{ Str::random(10) }}"
                                label="Tarih" wire:model="payment_date" />
                            <livewire:components.form.masraf_dropdown wire:model="masraf_id" />
                            <livewire:components.form.client_dropdown wire:model="client_id" />
                            <livewire:components.form.kasa_dropdown wire:model="kasa_id" />
                            <x-input label="Tutar" wire:model="price" suffix="₺" money />
                            <x-input label="Açıklama" wire:model="message" />
                            <x-slot:actions>
                                <x-button label="Gönder" icon="o-paper-airplane" type="submit" spinner="clientSave"
                                    class="btn-primary" />
                            </x-slot:actions>
                        </x-form>
                    </div>
                </x-tab>
            </x-tabs>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
            </x-slot:menu>
        </x-card>
    </div>
</div>
