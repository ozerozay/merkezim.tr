<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')] 
class extends \Livewire\Volt\Component {
    public $notifications = [];
    public $slides = [];

    public function mount() {
        // Burada sadece gerekli verileri yükle
        // Modal ile ilgili işlemleri kaldır
    }
};

?>
<div class="container mx-auto px-2 sm:px-4 space-y-4 sm:space-y-6">
    <!-- Header Actions -->
  

    <!-- Hero Section -->
    <div class="bg-base-100/95 backdrop-blur-sm rounded-xl p-4 sm:p-6 shadow-lg border border-base-200">
        <div class="flex flex-col items-center text-center gap-4 sm:gap-6">
            <div class="space-y-2 sm:space-y-3">
                <h1 class="text-3xl sm:text-4xl font-bold text-base-content">
                    MERHABA ✨
                </h1>
                <p class="text-base sm:text-lg text-base-content/70">
                    Güzelliğinize değer katın, size özel hizmetlerimizle tanışın
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                <x-button :link="auth()->check() ? route('client.profil.appointment') : route('client.profil.reservation-request')" 
                         class="btn btn-primary gap-2">
                    <span>{{ auth()->check() ? 'Randevu Al' : 'Rezervasyon' }}</span>
                    <span>📅</span>
                </x-button>
                <x-button :link="route('client.shop.packages')"
                         class="btn btn-outline gap-2">
                    <span>Paketleri İncele</span>
                    <span>🛍️</span>
                </x-button>
            </div>

            <!-- Mini Stats -->
            <div class="grid grid-cols-3 gap-2 sm:gap-4 w-full max-w-md">
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-primary">5K+</div>
                    <div class="text-[10px] sm:text-xs text-base-content/70">Mutlu Müşteri</div>
                </div>
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-primary">15+</div>
                    <div class="text-[10px] sm:text-xs text-base-content/70">Uzman Kadro</div>
                </div>
                <div class="text-center">
                    <div class="text-xl sm:text-2xl font-bold text-primary">12+</div>
                    <div class="text-[10px] sm:text-xs text-base-content/70">Yıllık Deneyim</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı İşlemler -->
    <div class="space-y-3 sm:space-y-4">
        <h2 class="text-lg sm:text-xl font-bold text-base-content flex items-center gap-2">
            <span class="text-primary">⚡️</span> Hızlı İşlemler
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 sm:gap-3">
            <!-- Online Satış -->
            <a href="{{ route('client.shop.packages') }}" class="group">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-xl">🛍️</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base-content text-sm">Online Satış</h3>
                            <p class="text-xs text-base-content/70">Paket ve Hizmetler</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Rezervasyon Talebi -->
            <a href="{{ route('client.profil.reservation-request') }}" class="group">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-xl">📅</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base-content text-sm">Rezervasyon</h3>
                            <p class="text-xs text-base-content/70">Online Randevu</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Teklif Talebi -->
            <div class="group cursor-pointer" wire:click="$dispatch('modal.open', {component: 'web.modal.request-offer-modal'})">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-xl">💫</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base-content text-sm">Teklif Al</h3>
                            <p class="text-xs text-base-content/70">Özel Teklifler</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yol Tarifi -->
            <a href="https://maps.google.com" target="_blank" class="group">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-xl">🗺️</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base-content text-sm">Yol Tarifi</h3>
                            <p class="text-xs text-base-content/70">Bize Ulaşın</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Sosyal Medya -->
            <div class="group cursor-pointer" x-data="{ open: false }">
                <div @click="open = true" class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-xl">📱</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base-content text-sm">Sosyal Medya</h3>
                            <p class="text-xs text-base-content/70">Bizi Takip Edin</p>
                        </div>
                    </div>
                </div>
                <!-- Sosyal Medya Popup -->
                <div x-show="open" 
                     x-cloak
                     @click.outside="open = false"
                     @keydown.escape.window="open = false"
                     x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/50 backdrop-blur-sm">
                    <div class="bg-base-100 rounded-xl p-6 shadow-xl max-w-sm w-full mx-4" @click.stop>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Sosyal Medya Hesaplarımız</h3>
                            <button @click="open = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="https://instagram.com" target="_blank" class="btn hover:bg-[#E4405F]/10 border-[#E4405F] hover:border-[#E4405F] gap-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="#E4405F" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                                </svg>
                                <span class="text-[#E4405F]">Instagram</span>
                            </a>
                            <a href="https://facebook.com" target="_blank" class="btn hover:bg-[#1877F2]/10 border-[#1877F2] hover:border-[#1877F2] gap-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                    <path fill="#1877F2" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                                </svg>
                                <span class="text-[#1877F2]">Facebook</span>
                            </a>
                            <a href="https://twitter.com" target="_blank" class="btn hover:bg-[#1DA1F2]/10 border-[#1DA1F2] hover:border-[#1DA1F2] gap-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="#1DA1F2" d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                                </svg>
                                <span class="text-[#1DA1F2]">Twitter</span>
                            </a>
                            <a href="https://youtube.com" target="_blank" class="btn hover:bg-[#FF0000]/10 border-[#FF0000] hover:border-[#FF0000] gap-2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                    <path fill="#FF0000" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/>
                                </svg>
                                <span class="text-[#FF0000]">YouTube</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bize Ulaşın -->
            <div class="group cursor-pointer" x-data="{ open: false }">
                <div @click="open = true" class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-xl">📞</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base-content text-sm">Bize Ulaşın</h3>
                            <p class="text-xs text-base-content/70">İletişim Bilgileri</p>
                        </div>
                    </div>
                </div>
                <!-- İletişim Popup -->
                <div x-show="open" 
                     x-cloak
                     @click.outside="open = false"
                     @keydown.escape.window="open = false"
                     x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/50 backdrop-blur-sm">
                    <div class="bg-base-100 rounded-xl p-6 shadow-xl max-w-sm w-full mx-4" @click.stop>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">İletişim Bilgilerimiz</h3>
                            <button @click="open = false" class="btn btn-ghost btn-sm btn-circle">✕</button>
                        </div>
                        <div class="space-y-4">
                            <a href="tel:+905555555555" class="flex items-center gap-3 p-3 rounded-lg hover:bg-base-200">
                                <span class="text-xl">📞</span>
                                <div>
                                    <div class="font-medium">Telefon</div>
                                    <div class="text-sm opacity-70">+90 555 555 55 55</div>
                                </div>
                            </a>
                            <a href="https://wa.me/905555555555" target="_blank" class="flex items-center gap-3 p-3 rounded-lg hover:bg-base-200">
                                <span class="text-xl">💬</span>
                                <div>
                                    <div class="font-medium">WhatsApp</div>
                                    <div class="text-sm opacity-70">+90 555 555 55 55</div>
                                </div>
                            </a>
                            <a href="mailto:info@example.com" class="flex items-center gap-3 p-3 rounded-lg hover:bg-base-200">
                                <span class="text-xl">✉️</span>
                                <div>
                                    <div class="font-medium">E-posta</div>
                                    <div class="text-sm opacity-70">info@example.com</div>
                                </div>
                            </a>
                            <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-base-200">
                                <span class="text-xl">📍</span>
                                <div>
                                    <div class="font-medium">Adres</div>
                                    <div class="text-sm opacity-70">İstanbul, Türkiye</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bildirimler -->
    @if(isset($notifications) && count($notifications))
        <div class="space-y-2 sm:space-y-3">
            <h2 class="text-lg sm:text-xl font-bold text-base-content flex items-center gap-2">
                <span class="text-primary">🔔</span> Bildirimler
            </h2>

            <div class="grid gap-3">
                @foreach($notifications as $notification)
                    <x-alert :title="$notification['title']"
                            :description="$notification['description']"
                            :icon="$notification['icon']"
                            :class="$notification['class']">
                        <x-slot:actions>
                            <x-button :label="$notification['action']" class="btn-sm" />
                        </x-slot:actions>
                    </x-alert>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Carousel -->
    @if(isset($slides) && count($slides))
        <div class="space-y-2 sm:space-y-3">
            <h2 class="text-lg sm:text-xl font-bold text-base-content flex items-center gap-2">
                <span class="text-primary">✨</span> Öne Çıkanlar
            </h2>
            <x-carousel :slides="$slides" class="rounded-lg overflow-hidden shadow-lg"/>
        </div>
    @endif
</div>
