<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.branch_multi_dropdown wire:key="{{ Str::random(10) }}" wire:model="branches"/>
        <x-choices-offline label="Satış" :options="$select_sale" wire:model="select_sale_id"
                           wire:key="{{ Str::random(10) }}" single/>
        <x-choices-offline label="Hizmet" :options="$select_service" wire:model="select_service_id"
                           wire:key="{{ Str::random(10) }}" single/>
        <x-choices-offline label="Taksit" :options="$select_taksit" wire:key="{{ Str::random(10) }}"
                           wire:model="select_taksit_id" single/>
        <x-choices-offline label="Randevu" :options="$select_appointment" wire:key="{{ Str::random(10) }}"
                           wire:model="select_appointment_id" single/>
        <x-choices-offline label="Ürün" :options="$select_product" wire:key="{{ Str::random(10) }}"
                           wire:model="select_product_id" single/>
        <x-choices-offline label="Label" :options="$select_label" wire:key="{{ Str::random(10) }}"
                           wire:model="select_label_id"/>
        <x-choices-offline label="Referans" :options="$select_referans" wire:key="{{ Str::random(10) }}"
                           wire:model="select_referans_id" single/>
        <x-choices-offline label="Cinsiyet" :options="$select_gender" wire:key="{{ Str::random(10) }}"
                           wire:model="select_gender_id" single/>
        <x-choices-offline label="Teklif" :options="$select_offer" wire:key="{{ Str::random(10) }}"
                           wire:model="select_offer_id" single/>
        <x-choices-offline label="Adisyon" :options="$select_adisyon" wire:key="{{ Str::random(10) }}"
                           wire:model="select_adisyon_id" single/>
    </x-slide-over>
</div>
