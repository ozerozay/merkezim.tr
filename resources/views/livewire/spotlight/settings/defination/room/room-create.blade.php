<div>
    <x-slide-over title="Oda Oluştur">
        <livewire:components.form.branch_dropdown wire:key="de33f-kas-{{ Str::random(10) }}" wire:model="branch_id" />
        <x-input label="Adı" wire:model="name" />
        <livewire:components.form.category_multi_dropdown wire:key="de33ssf-kas-{{ Str::random(10) }}"
            wire:model="category_ids" />
    </x-slide-over>
</div>
