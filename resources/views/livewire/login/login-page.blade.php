<div>
    <div class="overflow-x-hidden">
        <!-- Login Card -->
        <div class="bg-base-100/50 backdrop-blur-sm rounded-2xl border border-base-200 p-4 max-w-md mx-auto">
            <!-- Başlık -->
            <div class="flex flex-col gap-1 mb-4">
                <h2 class="text-xl font-medium text-base-content">Giriş Yapın</h2>
                <p class="text-sm text-base-content/70">7/24 İşlemlerinizi güvenle yapın.</p>
            </div>

            @if ($section == 'phone')
                <!-- Telefon Giriş Formu -->
                <form wire:submit="submit_phone" class="flex flex-col gap-4">
                    <!-- Telefon Input -->
                    <div>
                        <input type="tel" 
                               class="input w-full" 
                               wire:model="phone"
                               x-mask="9999999999"
                               placeholder="5xxxxxxxxx"
                               autofocus
                               inputmode="numeric">
                        <div class="text-[11px] text-base-content/70 mt-1">
                            5xxxxxxxxx şeklinde giriş yapın.
                        </div>
                    </div>

                    <!-- Giriş Butonu -->
                    <button type="submit" 
                            class="btn bg-gradient-to-r from-primary/90 to-primary hover:from-primary hover:to-primary/90 text-white border-0"
                            wire:loading.class="opacity-75"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submit_phone">
                            Giriş yap veya kayıt ol
                        </span>
                        <span wire:loading wire:target="submit_phone">
                            İşleniyor...
                        </span>
                    </button>

                    <!-- Sosyal Medya Girişleri -->
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <button wire:click="redirectTo('google')" 
                                class="btn btn-outline gap-2">
                            <span class="text-lg">🌐</span>
                            Google
                        </button>
                        <button wire:click="redirectTo('facebook')" 
                                class="btn btn-outline gap-2">
                            <span class="text-lg">👥</span>
                            Facebook
                        </button>
                    </div>
                </form>

            @elseif ($section == 'code')
                <!-- Kod Doğrulama Formu -->
                <form wire:submit="submitCode" class="flex flex-col gap-4">
                    <div class="text-sm text-base-content/70">
                        <p>Doğrulama kodunu girin.</p>
                        <p>+905056277636 nolu telefona gönderildi.</p>
                    </div>

                    <!-- Kod Input -->
                    <div x-data="{
                        value: @entangle('code'),
                        isSubmitting: false,
                        checkValue() {
                            if (this.value.length === 4 && !this.isSubmitting) {
                                this.isSubmitting = true;
                                $wire.call('submitCode', this.value)
                                    .then(() => { this.isSubmitting = false; })
                                    .catch(() => { this.isSubmitting = false; });
                            }
                        }
                    }">
                        <input type="text"
                               class="input w-full text-center text-2xl tracking-[1em] font-medium"
                               x-mask="9999"
                               maxlength="4"
                               inputmode="numeric"
                               autocomplete="one-time-code"
                               wire:model="code"
                               x-on:input="checkValue"
                               autofocus>
                    </div>

                    <!-- Geri & Tekrar Gönder -->
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" 
                                wire:click="backToPhone"
                                class="btn btn-outline col-span-1">
                            Geri Dön
                        </button>
                        
                        <div class="col-span-2" x-data="otpSend(10)" x-init="init()">
                            <template x-if="getTime() <= 0">
                                <button wire:click="resendOtp"
                                        class="btn btn-outline w-full">
                                    Tekrar gönder
                                </button>
                            </template>
                            <template x-if="getTime() > 0">
                                <button class="btn btn-outline w-full opacity-50" disabled>
                                    Tekrar göndermek için: <span x-text="formatTime(getTime())"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </form>

            @elseif ($section == 'form')
                <!-- Kayıt Formu -->
                <form wire:submit="submitForm" class="flex flex-col gap-4">
                    <p class="text-center text-xl font-medium mb-4">Son Bir Adım Kaldı</p>
                    
                    <!-- Form Alanları -->
                    <div class="space-y-4">
                        <x-select 
                            wire:key="branch-{{ Str::random(10) }}" 
                            label="Size en yakın şubemizi seçin"
                            wire:model="branch" 
                            :options="$branches" />

                        <x-input 
                            tabIndex="1" 
                            label="Adınız Soyadınız" 
                            wire:model="name" 
                            autofocus />

                        <livewire:components.form.gender_dropdown 
                            wire:key="e-dropdffown-{{ Str::random(10) }}"
                            wire:model="gender" 
                            :gender="1" 
                            :includeUniSex="false" />

                        <!-- Onay Kutuları -->
                        <div class="space-y-2">
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="ti"
                                       class="checkbox checkbox-sm mt-1">
                                <span class="text-xs">
                                    Kampanyalardan haberdar olmak için tarafıma ticari ileti gönderilsin
                                </span>
                            </label>

                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="kvk"
                                       class="checkbox checkbox-sm mt-1">
                                <span class="text-xs">
                                    Merkezim kullanım koşullarını, gizlilik ve KVKK politikasını ve aydınlatma metnini okudum, bu kapsamda verilerimin işlenmesini onaylıyorum
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Kayıt Butonu -->
                    <button type="submit" 
                            class="btn bg-gradient-to-r from-primary/90 to-primary hover:from-primary hover:to-primary/90 text-white border-0 mt-4"
                            wire:loading.class="opacity-75"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitForm">
                            Hadi Başlayalım
                        </span>
                        <span wire:loading wire:target="submitForm">
                            İşleniyor...
                        </span>
                    </button>
                </form>
            @endif

            <!-- Güvenlik İkonu -->
            <div class="flex justify-center mt-4">
                <span class="text-base-content/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>
</div>
