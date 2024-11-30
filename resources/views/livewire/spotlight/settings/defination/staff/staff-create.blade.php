<div>
    <x-slide-over title="Personel Oluştur">
        <livewire:components.form.client_dropdown wire:key="ci-{{ Str::random(10) }}" wire:model="client_id" />
        <livewire:components.form.branch_multi_dropdown wire:key="de33f-kas-{{ Str::random(10) }}"
            wire:model="branch_ids" />
        <x-select :options="$roles" label="Yetki" option-value="name" wire:model="role" />
    </x-slide-over>
</div>
