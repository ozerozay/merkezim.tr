<?php

new
#[\Livewire\Attributes\Layout('components.layouts.client')] 
class extends \Livewire\Volt\Component {

  
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
            <div class="group cursor-pointer" x-data="{ open: false }" @click="open = !open">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
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
                     @click.away="open = false"
                     x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/50 backdrop-blur-sm">
                    <div class="bg-base-100 rounded-xl p-6 shadow-xl max-w-sm w-full mx-4">
                        <h3 class="text-lg font-bold mb-4">Sosyal Medya Hesaplarımız</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="https://instagram.com" target="_blank" class="btn btn-outline gap-2">
                                <span class="text-xl">📸</span> Instagram
                            </a>
                            <a href="https://facebook.com" target="_blank" class="btn btn-outline gap-2">
                                <span class="text-xl">👥</span> Facebook
                            </a>
                            <a href="https://twitter.com" target="_blank" class="btn btn-outline gap-2">
                                <span class="text-xl">🐦</span> Twitter
                            </a>
                            <a href="https://youtube.com" target="_blank" class="btn btn-outline gap-2">
                                <span class="text-xl">📺</span> YouTube
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bize Ulaşın -->
            <div class="group cursor-pointer" x-data="{ open: false }" @click="open = !open">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-lg p-4 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
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
                     @click.away="open = false"
                     x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center bg-base-300/50 backdrop-blur-sm">
                    <div class="bg-base-100 rounded-xl p-6 shadow-xl max-w-sm w-full mx-4">
                        <h3 class="text-lg font-bold mb-4">İletişim Bilgilerimiz</h3>
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
