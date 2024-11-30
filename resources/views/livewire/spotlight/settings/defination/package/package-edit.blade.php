<div>
    <x-slide-over title="{{ $package->name ?? '' }}" subtitle="Paket Düzenle">
        <x-input label="Adı" wire:model="name" />
        <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
        <livewire:components.form.gender_dropdown wire:key="xoe-{{ Str::random(10) }}" wire:model="gender" />
        <livewire:components.form.number_dropdown wire:key="xo3e-{{ Str::random(10) }}" wire:model="buy_time"
            label="Ne kadar alınabilir ? (0 Sınırsız)" max="100" includeZero="true" />
        <x-button icon="o-plus-circle" label="Hizmet Ekle"
            wire:click="$dispatch('modal.open', {component: 'modals.select-package-service-modal', arguments: {'package': {{ $package->id }}}})" />
        @foreach ($package->items as $item)
            <x-list-item :item="$item" no-separator no-hover>
                @if ($item['gift'])
                    <x-slot:avatar>
                        <x-badge value="H" class="badge-primary" />
                    </x-slot:avatar>
                @endif
                <x-slot:value>
                    {{ $item->service->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $item->quantity }} seans
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                        wire:click="deleteItem({{ $item['id'] }})" spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
    </x-slide-over>
</div>
