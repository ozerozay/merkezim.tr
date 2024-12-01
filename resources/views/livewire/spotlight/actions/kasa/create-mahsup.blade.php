<div>
    <x-slide-over title="Mahsup">
        <livewire:components.form.date_time label="Tarih" wire:model="date" wire:key="date-field-{{ Str::random(10) }}" />
        <livewire:components.form.kasa_dropdown wire:key="k-s-{{ Str::random(10) }}" wire:model="cikis_kasa_id"
            label="Çıkış Kasası" />
        <livewire:components.form.kasa_dropdown wire:key="k-fd-{{ Str::random(10) }}" wire:model="giris_kasa_id"
            label="Giriş Kasası" />
        <x-input label="Tutar" wire:model="price" suffix="₺" money />
        <x-input label="Açıklama" wire:model="message" />
    </x-slide-over>
</div>
