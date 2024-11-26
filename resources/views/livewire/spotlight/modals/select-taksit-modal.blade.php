<x-custom-modal title="Taksit Oluştur">
    <x-slot:body>
        <livewire:components.form.date_time
            wire:key="date-fi2eld-{{ Str::random(10) }}"
            label="İlk Taksit Tarihi"
            wire:model="first_date"
        />
        <x-input label="Taksit Tutarı (Tek Taksit İçin)" wire:model="price" suffix="₺" money/>
        <livewire:components.form.number_dropdown
            wire:key="ddfguhdfg-{{ Str::random(10) }}"
            label="İlk Taksit Tarihi" wire:model="amount"/>
    </x-slot:body>
    <x-slot:actions>
        <x-button label="Ekle" type="submit" spinner="save" icon="o-paper-airplane"
                  class="btn-primary"/>
    </x-slot:actions>
</x-custom-modal>
