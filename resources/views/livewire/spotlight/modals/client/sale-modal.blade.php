<div class="overflow-x-hidden">
    <x-card title="Satış İşlemleri" separator progress-indicator>
        <x-slot:menu>
            <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
        </x-slot:menu>
        <x-accordion wire:model="group" separator class="bg-base-200">
            <x-collapse name="group1">
                <x-slot:heading>
                    <x-icon name="o-pencil" label="Düzenle" />
                </x-slot:heading>
                <x-slot:content>
                    <x-form wire:submit="edit">
                        <livewire:components.form.date_time wire:key="date-fi2eld-{{ Str::random(10) }}"
                            label="Satış Tarihi" wire:model="sale_date" />
                        <livewire:components.form.date_time wire:key="date-fi22eld-{{ Str::random(10) }}"
                            label="Son Kullanım Tarihi" wire:model="expire_date" />
                        <livewire:components.form.staff_multi_dropdown wire:key="drawer-sale-mlti-dropdown"
                            wire:model="sale_staffs" />
                        <livewire:components.form.sale_type_dropdown wire:key="drawer-sale-type-dropdown"
                            wire:model="sale_type" />
                        <x-input label="Açıklama" wire:model="messageEdit" />
                        <x-slot:actions>
                            <x-button label="Gönder" type="submit" spinner="edit" class="btn-primary" />
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>
            <x-collapse name="group2">
                <x-slot:heading>
                    <x-icon name="o-pencil" label="Durum Düzenle" />
                </x-slot:heading>
                <x-slot:content>
                    <x-form wire:submit="changeStatus">
                        <livewire:components.form.active_cancel_status_dropdown wire:model.live="sale_status"
                            :expireFreeze="true" />
                        <livewire:components.form.date_time wire:key="date-xx-{{ Str::random(10) }}"
                            label="Ne zaman açılsın ? - Dondurmak için doldurun." wire:model="freeze_date" />
                        <x-input label="Açıklama" wire:model="messageStatus" />
                        <x-slot:actions>
                            <x-button label="Gönder" type="submit" spinner="changeStatus" class="btn-primary" />
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>
            <x-collapse name="group3">
                <x-slot:heading>
                    <x-icon name="o-minus-circle" label="Sil" />
                </x-slot:heading>
                <x-slot:content>
                    <x-form wire:submit="delete">
                        <x-alert title="Emin misiniz ?"
                            description="Satışı silmeniz durumunda ona bağlı kasa işlemleri, hizmetler ve taksitlerde silinir."
                            icon="o-minus-circle" class="alert-error" />
                        <x-input label="Açıklama" wire:model="messageDelete" />
                        <x-slot:actions>
                            <x-button label="Gönder" type="submit" class="btn-error" />
                        </x-slot:actions>
                    </x-form>
                </x-slot:content>
            </x-collapse>
        </x-accordion>
    </x-card>
</div>
