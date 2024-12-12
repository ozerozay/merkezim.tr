<div>
    <div class="overflow-x-hidden">
        <x-card title="Kupon İşlemleri" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
            </x-slot:menu>
            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Düzenle" />
                    </x-slot:heading>
                    <x-slot:content>

                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil" />
                    </x-slot:heading>
                    <x-slot:content>

                    </x-slot:content>
                </x-collapse>
            </x-accordion>
        </x-card>
    </div>
</div>
