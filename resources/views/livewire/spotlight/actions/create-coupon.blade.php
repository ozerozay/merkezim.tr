<div>
    <x-slide-over title="Kupon Oluştur" subtitle="{{ $client->name ?? '' }}">
        <x-input label="Kupon Kodu" wire:model="code">
            <x-slot:append>
                <x-button label="Rastgele Oluştur" wire:click="generateCode" icon="o-check"
                          class="btn-primary rounded-s-none"/>
            </x-slot:append>
        </x-input>
        <div class="flex">
            <div class="w-1/2">
                <x-radio label="İndirim Çeşidi" :options="$discount_types" wire:model.live="discount_type"/>
            </div>
            <div class="w-1/2">
                @if ($discount_type)
                    <livewire:components.form.number_dropdown
                        wire:key="numb-{{ Str::random(10) }}"
                        wire:model="discount_amount" suffix="%" max="100"
                        label="İndirim Yüzdesi" :includeZero="false"/>
                @else
                    <x-input label="İndirim Tutarı (TL)" wire:model="discount_amount" suffix="₺" money/>
                @endif
            </div>
        </div>
        <livewire:components.form.number_dropdown
            wire:key="numbss-{{ Str::random(10) }}"
            wire:model="count" suffix="Adet" max="100" label="Kupon Adedi"
            :includeZero="false"/>
        <x-input label="Minimum Sipariş Tutarı (TL) - 0 Sınır Yok" wire:model="min_order" suffix="₺" money/>
        <livewire:components.form.date_time
            wire:key="date-fieee2eld-{{ Str::random(10) }}"
            label="Son Geçerlilik Tarihi"
            wire:model="end_date"
        />
    </x-slide-over>
</div>
