<div>

    <div class="overflow-x-hidden">
        <x-card title="Giriş Yapın" subtitle="7/24 İşlemlerinizi güvenle yapın." separator progress-indicator>

            @if ($section == 'phone')
                <x-form wire:submit="submit_phone">
                    <x-input autofocus inputmode="numeric" label="Telefon Numaranız" wire:model="phone" icon="o-phone"
                        autofocus x-mask="9999999999" hint="5xxxxxxxxx şeklinde giriş yapın." />
                    <x-slot:actions>
                        <x-button label="Giriş yap veya kayıt ol" type="submit" icon="o-paper-airplane"
                            class="btn btn-primary w-full mb-4" spinner="submit_phone" />
                    </x-slot:actions>
                </x-form>
            @elseif ($section == 'code')
                <x-form wire:submit="submitCode">
                    <p>Doğrulama kodunu girin.</p>
                    <p>+905056277636 nolu telefona gönderildi.</p>
                    <div x-data="{
                        value: @entangle('code'),
                        checkValue() {
                            if (this.value.length === 4) {
                                $wire.call('submitCode', this.value);
                            }
                        }
                    }">
                        <x-input autofocus autocomplete="one-time-code" inputmode="numeric" label="Kod"
                            wire:model="code" icon="o-phone" x-mask="9999" x-on:input="checkValue"
                            hint="Telefonunuza gönderilen 4 haneli kodu girin." />
                    </div>
                    <x-slot:actions>
                        <x-button label="Giriş" type="submit" icon="o-paper-airplane" class="btn btn-primary w-full"
                            spinner="submitCode" />
                    </x-slot:actions>
                </x-form>
                <x-hr />
                <div class="grid gap-1 grid-cols-3">
                    <x-button class="w-full btn-outline col-span-1" wire:click="backToPhone">
                        Geri Dön
                    </x-button>
                    <div class="grid gap-y-2 text-center col-span-2" x-data="otpSend(10)" x-init="init()">
                        <template x-if="getTime() <= 0">
                            <form wire:submit="resendOtp">
                                <x-button class="w-full btn-outline">
                                    Tekrar gönder
                                </x-button>
                                <input type="hidden" wire:model="otp">
                            </form>
                        </template>
                        <template x-if="getTime() > 0">
                            <small>
                                <x-button class="w-full btn-outline" disabled>
                                    Tekrar göndermek için: <span x-text="formatTime(getTime())"></span>
                                </x-button>
                            </small>
                        </template>
                    </div>
                </div>
            @elseif ($section == 'form')
                <x-form wire:submit="submitForm">
                    <p class="text-center text-xl">Son Bir Adım Kaldı</p>
                    <x-select wire:key="branch-{{ Str::random(10) }}" label="Size en yakın şubemizi seçin"
                        wire:model="branch" :options="$branches" />
                    <x-input tabIndex="1" label="Adınız Soyadınız" icon="tabler.user" wire:model="name" autofocus />

                    <livewire:components.form.gender_dropdown wire:key="e-dropdffown-{{ Str::random(10) }}"
                        wire:model="gender" :gender="1" :includeUniSex="false" />
                    <x-checkbox wire:model="ti"
                        label="Kampanyalardan haberdar olmak için tarafıma ticari ileti gönderilsin" class="text-xxs" />
                    <x-checkbox wire:model="kvk"
                        label="Merkezim kullanım koşullarını, gizlilik ve KVKK politikasını ve aydınlatma metnini okudum, bu kapsamda verilerimin işlenmesini onaylıyorum" />
                    <x-slot:actions>
                        <x-button label="Hadi Başlayalım" type="submit" icon="o-paper-airplane"
                            class="btn btn-primary w-full mb-4" spinner="submit_phone" />
                    </x-slot:actions>
                </x-form>
            @endif
            <x-slot:menu>
                <x-button icon="tabler.lock" class="btn-outline" tabIndex="-1" />
            </x-slot:menu>
        </x-card>

    </div>

</div>
