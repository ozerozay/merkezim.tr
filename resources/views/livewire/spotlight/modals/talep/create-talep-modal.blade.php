<div>
    <x-slide-over title="Talep Oluştur">
        <livewire:components.form.branch_dropdown wire:key="ttxammg-{{ Str::random(5) }}" wire:model="branch_id" />
        <x-input label="Adı" wire:model="name" />
        <livewire:components.form.phone wire:key="ttxxmmg-{{ Str::random(5) }}" wire:model="phone" />
        <livewire:components.form.talep_type_dropdown wire:key="ttmmg-{{ Str::random(5) }}" wire:model="type" />
        <x-input label="Açıklama" wire:model="message" />
    </x-slide-over>
</div>
