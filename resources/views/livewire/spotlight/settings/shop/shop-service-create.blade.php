<div>
    <x-slide-over title="Hizmet Tanımla">
        <livewire:components.form.branch_dropdown wire:key="de33f-kas-{{ Str::random(10) }}"
            wire:model.live="branch_id" />
        <x-choices-offline wire:key="scssdexczaa" wire:model="service_id" single searchable :options="$services"
            option-sub-label="name" option-sub-label="price" label="Hizmet" icon="o-magnifying-glass" />
        <x-input label="Adı" wire:model="name" />
        <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
        <livewire:components.form.number_dropdown wire-key="xx{{ Str::random(10) }}" wire:model="buy_max"
            label="En fazla kaç adet alınabilir ?(0 sınırsız)" max="100" includeZero="true" />
        <livewire:components.form.number_dropdown wire-key="xx{{ Str::random(10) }}" wire:model="buy_min"
            label="En az kaç adet alınabilir ?(0 sınırsız)" max="100" includeZero="true" />
        <x-input label="İndirim Yazısı (Boş bırakabilirsiniz)" hint="Paket kartının sağ üstündeki yazı (%40 İNDİRİM)"
            wire:model="discount_text" />
        <livewire:components.form.number_dropdown wire-key="xx{{ Str::random(10) }}" wire:model="kdv" label="KDV"
            max="99" suffix="%" includeZero="true" />
        <x-input wire:model="description" label="Açıklama" />
    </x-slide-over>
</div>
