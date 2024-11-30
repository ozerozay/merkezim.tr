<div>
    <x-slide-over title="{{ $service->name ?? '' }}" subtitle="Hizmet Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $service->id }}" />
        <x-input label="Adı" wire:model="name" />
        <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
        <livewire:components.form.number_dropdown wire:key="xx{{ Str::random(10) }}" wire:model="seans"
            label="Seans Sayısı (Takas için)" max="100" includeZero="false" />
        <livewire:components.form.number_dropdown wire:key="xxx{{ Str::random(10) }}" wire:model="duration"
            label="Hizmet Süresi (Dakika)" max="400" includeZero="false" />
        <livewire:components.form.gender_dropdown wire:key="xxx{{ Str::random(10) }}" wire:model="gender" />
        <livewire:components.form.number_dropdown wire:key="xxxxx{{ Str::random(10) }}" wire:model="min_day"
            label="Minimum Süre (Gün)" max="100" includeZero="false" />
        <x-checkbox label="Bu hizmeti danışan görebilir mi ?" wire:model="visible" hint="Online İşlem Merkezi" />
    </x-slide-over>
</div>
