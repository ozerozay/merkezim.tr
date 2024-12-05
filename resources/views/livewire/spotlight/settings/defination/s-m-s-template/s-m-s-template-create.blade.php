<div>
    <x-slide-over title="SMS Şablonu Oluştur">
        <livewire:components.form.branch_dropdown wire:key="de33f-kas-{{ Str::random(10) }}" wire:model="branch_id" />
        <x-input label="Adı" wire:model="name" />
        <x-textarea label="Mesaj" wire:model="message" />
    </x-slide-over>
</div>
