<div>
    <x-slide-over title="Ödeme Takip Oluştur">
        <x-radio label="Sıklık" :options="$frequencyList" wire:model.live="frequency" />
        <livewire:components.form.branch_dropdown wire:key="cvvfdv{{ Str::random(10) }}" wire:model="branch" />
        <x-input label="Ödeme Adı" wire:model="name" />
        <livewire:components.form.date_time wire:key="date-fi2eld-{{ Str::random(10) }}" label="Başlangıç Tarihi"
            wire:model="date" />
        <x-input label="Tutar - Boş bırakabilirsiniz. Fatura vb ödemeler için" wire:model="price" suffix="₺"
            money />
        @if ($frequency != 'tek')
            <livewire:components.form.number_dropdown wire:key="date-fi2exxld-{{ Str::random(10) }}"
                wire:model="installment" label="Taksit" />
        @endif
        <x-input label="Açıklama" wire:model="message" />

    </x-slide-over>
</div>
