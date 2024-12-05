<div>
    <x-slide-over title="SMS Gönder" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.form.client_dropdown wire:key="client-field-{{ Str::random(10) }}"
            wire:model.live="client_id" />
        <livewire:components.form.phone wire:key="phone-field-{{ Str::random(10) }}" wire:model="phone" />
        <livewire:components.form.sms_template_dropdown wire:key="template-field-{{ Str::random(10) }}"
            wire:model.live="template" />
        <div class="" x-data="{ count: 0, units: 0 }" x-init="count = $refs.countme.value.length;
        units = Math.ceil(count / 150)">
            <x-textarea rows="3" label="Mesajınız" wire:model="message" x-ref="countme"
                x-on:keyup="count = $refs.countme.value.length; units = Math.ceil(count / 155)" />
            <div class="">
                Karakter: <span x-html="count"></span> | Kontör: <span x-html="units"></span>
            </div>
        </div>
    </x-slide-over>
</div>
