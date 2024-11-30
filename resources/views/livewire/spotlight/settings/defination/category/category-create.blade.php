<div>
    <x-slide-over title="Kategori Oluştur">
        <livewire:components.form.branch_multi_dropdown wire:key="de33f-kas-{{ Str::random(10) }}"
            wire:model="branch_ids" />
        <x-input label="Adı" wire:model="name" />
        <livewire:components.form.number_dropdown label="Kaç seans kullanıldığında hediye kazanılacak ?"
            wire:key="ndf-{{ Str::random(10) }}" wire:model="earn" :includeZero="true" suffix="adet" />
    </x-slide-over>
</div>
