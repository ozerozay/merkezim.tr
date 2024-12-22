<div>
    <x-slide-over title="Düzenle">
        <h2 class="font-bold">{{ \App\Traits\StringHelper::strUpper( $occurrence->agenda->name) }}</h2>
        <p class="break-all">{{ $occurrence->agenda->message }}</p>
        <livewire:components.form.agenda_status_dropdown wire:model="status" wire:key="cc-{{Str::random()}}"/>
        @if($occurrence->agenda->type == \App\AgendaType::payment)
            <x-input label="Tutar" wire:model="price" suffix="₺" money/>
        @endif
        <livewire:components.form.date_time
            label="Tarih"
            wire:model="date"
            wire:key="date-field-{{ Str::random(10) }}"
        />
        <x-input label="Ad" wire:model="name"/>
        <x-textarea label="Mesaj" wire:model="message"/>
        <x-button class="btn-block btn-outline btn-error" wire:click="delete" wire:confirm="Emin misiniz ?">BU VE BAĞLI
            BULUNDUĞU İŞLEMLERİ
            SİL
        </x-button>
    </x-slide-over>
</div>
