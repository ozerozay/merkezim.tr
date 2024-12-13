<div>
    <x-header title="Kurulum Sihirbazı" separator progress-indicator />
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-card title="Hizmet Kategorileri" class="bg-base-300" separator progress-indicator>
            <x-slot:title>
                <p class="line-through">Hizmet Kategorileri</p>
            </x-slot:title>
            <x-list-item :item="[]" no-separator no-hover>
                <x-slot:value>
                    BAKIRKÖY
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="o-check-circle" class="text-green-500 btn-sm" />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="[]" no-separator no-hover>
                <x-slot:value>
                    MECİDİYEKÖY
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="o-check-circle" class="text-green-500 btn-sm" />
                </x-slot:actions>
            </x-list-item>

            <x-slot:actions>
                <x-button icon="tabler.plus" class="btn-primary" label="Hizmet Kategorilerini Düzenle"
                    wire:click="$dispatch('slide-over.open', {'component': 'settings.defination.category.category-defination'})" />
            </x-slot:actions>
        </x-card>
        <x-card title="Hizmet" separator progress-indicator>
            <x-list-item :item="[]" no-separator no-hover>
                <x-slot:value>
                    BAKIRKÖY
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="tabler.x" class="text-red-500 btn-sm" />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="[]" no-separator no-hover>
                <x-slot:value>
                    MECİDİYEKÖY
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="o-check-circle" class="text-green-500 btn-sm" />
                </x-slot:actions>
            </x-list-item>

            <x-slot:actions>
                <x-button icon="tabler.plus" class="btn-primary" label="Hizmet Ekle"
                    wire:click="$dispatch('slide-over.open', {'component': 'settings.defination.service.service-defination'})" />
            </x-slot:actions>
        </x-card>
        <x-card title="Hizmet Odaları" separator progress-indicator>
            <x-list-item :item="[]" no-separator no-hover>
                <x-slot:value>
                    MECİDİYEKÖY
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="o-check-circle" class="text-green-500 btn-sm" />
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="[]" no-separator no-hover>
                <x-slot:value>
                    BAKIRKÖY
                </x-slot:value>
                <x-slot:actions>
                    <x-button icon="tabler.x" class="text-red-500 btn-sm" />
                </x-slot:actions>
            </x-list-item>
            <x-slot:actions>
                <x-button icon="tabler.plus" class="btn-primary" label="Hizmet Odası Ekle"
                    wire:click="$dispatch('slide-over.open', {'component': 'settings.defination.room.room-defination'})" />
            </x-slot:actions>
        </x-card>
    </div>
</div>
