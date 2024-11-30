<div>
    <x-slide-over title="Personel Düzenle">
        <livewire:components.form.branch_multi_dropdown wire:key="de33f-kas-{{ Str::random(10) }}"
            wire:model="staff_branches" />
        <x-select :options="$roles" label="Yetki" option-value="name" wire:model="role" />
        <x-checkbox wire:model="can_login" class="self-start" label="Sisteme giriş yapabilir mi ?" />
        <x-checkbox wire:model="active" class="self-start" label="Aktif mi ?" />
    </x-slide-over>
</div>
