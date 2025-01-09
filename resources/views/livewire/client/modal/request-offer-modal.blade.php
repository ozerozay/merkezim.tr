<div>
    <x-custom-modal title="Teklif İste">
        <x-slot:body>
            @guest
                <div class="space-y-4 mb-4">
                    <x-input label="Adınız Soyadınız"
                            wire:model="name"
                            placeholder="Adınız ve soyadınızı giriniz"/>
                            
                    <x-input label="E-posta Adresiniz"
                            wire:model="email"
                            placeholder="E-posta adresinizi giriniz"/>
                            
                    <x-input label="Telefon Numaranız"
                            wire:model="phone"
                            placeholder="Telefon numaranızı giriniz"/>
                </div>
            @endguest

            <x-textarea label="Mesajınız"
                       wire:model="message"
                       placeholder="Teklif almak istediğiniz hizmetlerimizi detaylıca yazabilirsiniz."/>
        </x-slot:body>
    </x-custom-modal>
</div>
