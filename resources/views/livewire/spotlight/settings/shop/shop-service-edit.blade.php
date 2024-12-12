<div>
    <x-slide-over title="{{ $service->name ?? '' }}" subtitle="Hizmet Düzenle">
        <x-toggle label="Aktif" wire:model="active" wire:key="tgvxscb-{{ $service->id }}" />
        <x-input label="Adı" wire:model="name" />
        <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
        <livewire:components.form.number_dropdown wire-key="xx{{ Str::random(10) }}" wire:model="buy_max"
            label="En fazla kaç adet alınabilir ?(0 sınırsız)" max="100" includeZero="true" />
        <livewire:components.form.number_dropdown wire-key="xa{{ Str::random(10) }}" wire:model="buy_min"
            label="En az kaç adet alınabilir ?(0 sınırsız)" max="100" includeZero="true" />
        <x-input label="İndirim Yazısı (Boş bırakabilirsiniz)" hint="Paket kartının sağ üstündeki yazı (%40 İNDİRİM)"
            wire:model="discount_text" />
        <livewire:components.form.number_dropdown wire-key="xb{{ Str::random(10) }}" wire:model="kdv" label="KDV"
            max="99" suffix="%" includeZero="true" />
        <x-input wire:model="description" label="Açıklama" />
    </x-slide-over>
</div>
