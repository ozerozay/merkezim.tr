<div>
    <div class="overflow-x-hidden">
        <x-card title="Adisyon İşlemleri" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
            </x-slot:menu>

            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil" />
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="delete">
                            <x-alert title="Emin misiniz ?" description="Silme işlemi geri alınamaz."
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
</div>
