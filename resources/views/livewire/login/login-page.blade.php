<div class="min-h-screen min-w-full flex items-center justify-center bg-gradient-to-tr from-primary/5 via-base-100 to-secondary/5 overflow-hidden">
    <!-- Sol Taraftaki Dekoratif Daire -->
    <div class="fixed left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-gradient-to-tr from-primary/20 to-primary/5 rounded-full blur-3xl"></div>
    
    <!-- Sağ Taraftaki Dekoratif Daire -->
    <div class="fixed right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-bl from-secondary/20 to-secondary/5 rounded-full blur-3xl"></div>

    <div class="w-full sm:max-w-md md:max-w-lg lg:max-w-xl mx-auto p-4 sm:p-6 relative">
        <!-- Card Container -->
        <div class="bg-base-100/70 backdrop-blur-xl rounded-3xl border border-base-200 shadow-xl">
            <!-- Değişen Başlık -->
            <div class="p-6 sm:p-8 text-center" x-data="greetings" x-init="startRotation()">
                <h1 class="text-4xl sm:text-5xl font-bold mb-3 bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    <span x-text="currentGreeting.text" 
                          x-transition:enter="transition ease-out duration-300"
                          x-transition:enter-start="opacity-0 transform translate-y-4"
                          x-transition:enter-end="opacity-100 transform translate-y-0"
                          x-transition:leave="transition ease-in duration-300"
                          x-transition:leave-start="opacity-100 transform translate-y-0"
                          x-transition:leave-end="opacity-0 transform -translate-y-4"
                    ></span>
                </h1>
                <p class="text-base-content/70 text-lg">Güzellik yolculuğunuz başlıyor</p>
            </div>

            <div class="p-6 sm:p-8 pt-0">
                @if ($section == 'phone')
                    <!-- Telefon Formu -->
                    <form wire:submit="submit_phone" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-base-content/70 text-sm pl-1">
                                Telefon Numaranız
                            </label>
                            <div x-data="{ 
                                phone: @entangle('phone'),
                                watchPhone() {
                                    this.$watch('phone', value => {
                                        if (value && value.length === 10) {
                                            this.$wire.submit_phone()
                                        }
                                    })
                                }
                            }" x-init="watchPhone" class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-xl">📱</span>
                                </div>
                                <input type="tel" 
                                       class="input input-bordered w-full pl-12 h-14 text-lg" 
                                       wire:model.live="phone"
                                       x-mask="9999999999"
                                       placeholder="Örn: 5321234567"
                                       autofocus
                                       inputmode="numeric">
                            </div>
                            <p class="text-xs text-base-content/50 pl-1">
                                Size ulaşabilmemiz için telefon numaranızı girin
                            </p>
                        </div>

                        <button type="submit" 
                                class="btn btn-primary w-full h-14 text-lg">
                            <span wire:loading.remove wire:target="submit_phone">
                                Hadi Başlayalım 🚀
                            </span>
                            <span wire:loading wire:target="submit_phone">
                                İşleniyor... ⏳
                            </span>
                        </button>

                        <!-- Sosyal Medya -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-base-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 py-1 bg-base-100/70 backdrop-blur-xl text-base-content/70 rounded-full border border-base-200">
                                    Veya şununla devam et
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <button wire:click="redirectTo('google')" 
                                    type="button"
                                    class="btn bg-white hover:bg-slate-50 border-slate-200 hover:border-slate-300 gap-3 w-full h-14 text-slate-600">
                                <svg class="w-6 h-6" viewBox="0 0 24 24">
                                    <path fill="#EA4335" d="M5.266 9.765A7.077 7.077 0 0 1 12 4.909c1.69 0 3.218.6 4.418 1.582L19.91 3C17.782 1.145 15.055 0 12 0 7.27 0 3.198 2.698 1.24 6.65l4.026 3.115Z"/>
                                    <path fill="#34A853" d="M16.04 18.013c-1.09.703-2.474 1.078-4.04 1.078a7.077 7.077 0 0 1-6.723-4.823l-4.04 3.067A11.965 11.965 0 0 0 12 24c2.933 0 5.735-1.043 7.834-3l-3.793-2.987Z"/>
                                    <path fill="#4A90E2" d="M19.834 21c2.195-2.048 3.62-5.096 3.62-9 0-.71-.109-1.473-.272-2.182H12v4.637h6.436c-.317 1.559-1.17 2.766-2.395 3.558L19.834 21Z"/>
                                    <path fill="#FBBC05" d="M5.277 14.268A7.12 7.12 0 0 1 4.909 12c0-.782.125-1.533.357-2.235L1.24 6.65A11.934 11.934 0 0 0 0 12c0 1.92.445 3.73 1.237 5.335l4.04-3.067Z"/>
                                </svg>
                                Google ile giriş yap
                            </button>
                            <button wire:click="redirectTo('facebook')" 
                                    type="button"
                                    class="btn bg-[#1877F2] hover:bg-[#1874EA] text-white gap-3 w-full h-14 border-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                Facebook ile giriş yap
                            </button>
                        </div>
                    </form>

                @elseif ($section == 'code')
                    <!-- Kod Doğrulama -->
                    <form wire:submit="submitCode" class="space-y-6">
                        <div class="text-center space-y-3">
                            <span class="text-5xl">📲</span>
                            <p class="font-medium text-lg">Doğrulama kodunu girin</p>
                            <p class="text-base-content/70">+90{{ $phone }} nolu telefona gönderildi</p>
                        </div>

                        <div x-data="{ 
                            code: @entangle('code'),
                            watchCode() {
                                this.$watch('code', value => {
                                    if (value && value.length === 4) {
                                        this.$wire.submitCode()
                                    }
                                })
                            }
                        }" x-init="watchCode" class="my-8">
                            <input type="text"
                                   class="input input-bordered w-full text-center text-3xl tracking-[1em] font-medium h-16"
                                   x-mask="9999"
                                   maxlength="4"
                                   inputmode="numeric"
                                   wire:model.live="code"
                                   autofocus>
                        </div>

                        <div class="space-y-4">
                            <button type="submit" 
                                    class="btn btn-primary w-full h-14 text-lg">
                                <span wire:loading.remove wire:target="submitCode">
                                    Kodu Doğrula ✓
                                </span>
                                <span wire:loading wire:target="submitCode">
                                    Doğrulanıyor... ⏳
                                </span>
                            </button>

                            <button type="button" 
                                    wire:click="backToPhone"
                                    class="btn btn-outline w-full h-14">
                                ← Geri
                            </button>
                            
                            <div x-data="otpSend(60)" x-init="init()">
                                <template x-if="getTime() <= 0">
                                    <button wire:click="resendOtp"
                                            type="button"
                                            class="btn btn-outline w-full h-14">
                                            🔄 Tekrar Gönder
                                    </button>
                                </template>
                                <template x-if="getTime() > 0">
                                    <button class="btn btn-outline w-full h-14 opacity-50" disabled>
                                        ⏳ <span x-text="formatTime(getTime())"></span> saniye sonra tekrar gönderebilirsiniz
                                    </button>
                                </template>
                            </div>
                        </div>
                    </form>

                @elseif ($section == 'form')
                    <!-- Kayıt Formu -->
                    <form wire:submit="submitForm" class="space-y-6">
                        <div class="text-center space-y-3">
                            <span class="text-5xl">✨</span>
                            <h3 class="text-2xl font-medium">Son Bir Adım Kaldı</h3>
                            <p class="text-base-content/70">Sizi daha iyi tanıyalım</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-base-content/70 text-sm pl-1">
                                    Şube Seçimi
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-xl">🏢</span>
                                    </div>
                                    <select wire:model="branch" class="select select-bordered w-full pl-12 h-14 text-lg">
                                        <option value="">Size en yakın şubeyi seçin</option>
                                        @foreach($branches as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-xs text-base-content/50 pl-1">
                                    Hizmet almak istediğiniz şubeyi seçin
                                </p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-base-content/70 text-sm pl-1">
                                    Ad Soyad
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-xl">👤</span>
                                    </div>
                                    <input type="text"
                                           class="input input-bordered w-full pl-12 h-14 text-lg"
                                           wire:model="name"
                                           placeholder="Örn: Ayşe Yılmaz"
                                           autofocus>
                                </div>
                                <p class="text-xs text-base-content/50 pl-1">
                                    Size nasıl hitap etmemizi istersiniz?
                                </p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-base-content/70 text-sm pl-1">
                                    Cinsiyet
                                </label>
                                <livewire:components.form.gender_dropdown 
                                    wire:model="gender" 
                                    :gender="1" 
                                    :includeUniSex="false" />
                                <p class="text-xs text-base-content/50 pl-1">
                                    Size özel hizmetler sunabilmemiz için cinsiyetinizi seçin
                                </p>
                            </div>

                            <label class="flex items-start gap-3 cursor-pointer hover:bg-base-200 p-3 rounded-xl transition-colors">
                                <input type="checkbox" 
                                       wire:model="ti"
                                       class="checkbox checkbox-sm mt-1">
                                <span class="text-base">
                                    📬 Kampanyalardan haberdar olmak için tarafıma ticari ileti gönderilsin
                                </span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer hover:bg-base-200 p-3 rounded-xl transition-colors">
                                <input type="checkbox" 
                                       wire:model="kvk"
                                       class="checkbox checkbox-sm mt-1">
                                <span class="text-base">
                                    📋 Kullanım koşullarını ve KVKK metnini okudum, onaylıyorum
                                </span>
                            </label>
                        </div>

                        <button type="submit" 
                                class="btn btn-primary w-full h-14 text-lg">
                            <span wire:loading.remove wire:target="submitForm">
                                Hadi Başlayalım ✨
                            </span>
                            <span wire:loading wire:target="submitForm">
                                İşleniyor... ⏳
                            </span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('greetings', () => ({
            greetings: [
                { text: 'Merhaba', lang: 'tr' },
                { text: 'Hello', lang: 'en' },
                { text: 'Hola', lang: 'es' },
                { text: 'Bonjour', lang: 'fr' },
                { text: 'こんにちは', lang: 'jp' }
            ],
            currentIndex: 0,
            currentGreeting: { text: 'Merhaba', lang: 'tr' },
            startRotation() {
                setInterval(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.greetings.length;
                    this.currentGreeting = this.greetings[this.currentIndex];
                }, 3000);
            }
        }));
    });

    function otpSend(num) {
        return {
            countDown: num * 1000,
            countDownTimer: Date.now() + (num * 1000),
            intervalID: null,
            init() {
                if (!this.intervalID) {
                    this.intervalID = setInterval(() => {
                        this.countDown = this.countDownTimer - Date.now();
                    }, 1000);
                }
            },
            getTime() {
                if (this.countDown < 0) {
                    this.clearTimer();
                }
                return this.countDown;
            },
            formatTime(ms) {
                const seconds = Math.floor(ms / 1000);
                return `${Math.floor(seconds / 60)}:${(seconds % 60).toString().padStart(2, '0')}`;
            },
            clearTimer() {
                clearInterval(this.intervalID);
            }
        }
    }
</script>