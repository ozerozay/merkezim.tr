<div>
    <x-custom-modal title="Teklif İste">
        <x-slot:body>
            <x-textarea label="Mesajınız"
                        autofocus
                        wire:model="message"
                        placeholder="Teklif almak istediğiniz hizmetlerimizi detaylıca yazabilirsiniz."/>
        </x-slot:body>
    </x-custom-modal>
</div>
