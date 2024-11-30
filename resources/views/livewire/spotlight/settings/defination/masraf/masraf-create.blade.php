<div>
    <x-slide-over title="Masraf Oluştur">
        <livewire:components.form.branch_dropdown wire:key="de33f-kas-{{ Str::random(10) }}" wire:model="branch_id" />
        <x-input label="Adı" wire:model="name" />
    </x-slide-over>
</div>
