<div>
    <x-slide-over title="Hatırlatma - Randevu Oluştur">
        <livewire:components.form.client_dropdown wire:key="ffghghg-{{ Str::random(5) }}"
            label="Danışan - Boş bırakabilirsiniz." wire:model="client" />
        <x-hr />
        <livewire:components.form.branch_dropdown wire:key="bcsw-{{ Str::random(5) }}" wire:model="branch" />
        <x-input label="Adı" wire:model="name" />
        <x-select label="Çeşit" wire:model="type" :options="$typeList" />
        <livewire:components.form.date_time wire:key="datexx-{{ Str::random(10) }}" :enableTime="true" label="Tarih"
            wire:model="date" />
        <x-input label="Notunuz" wire:model="message" />
        <x:slot:actions>
            <x-button label="Gönder" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary" />
        </x:slot:actions>
    </x-slide-over>
</div>
