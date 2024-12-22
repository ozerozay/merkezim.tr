<div>
    <div class="overflow-x-hidden">
        <x-card title="Ürün Satışı İşlemleri" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>

            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group0">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Düzenle"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="edit">
                            <livewire:components.form.date_time
                                wire:key="date-xa-{{ Str::random(10) }}"
                                label="Satış Tarihi" wire:model="product_sale_date"/>
                            <livewire:components.form.staff_multi_dropdown
                                wire:key="drawer-sale-mlti-{{Str::random(10)}}"
                                wire:model="product_sale_staffs"/>
                            <x-input label="Açıklama" wire:model="messageEdit"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="edit" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="delete">
                            <x-alert title="Emin misiniz ?" description="Silme işlemi geri alınamaz."
                                     icon="o-minus-circle" class="alert-error"/>
                            <x-input label="Açıklama" wire:model="messageDelete"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" class="btn-error"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            </x-accordion>
        </x-card>
    </div>
</div>
