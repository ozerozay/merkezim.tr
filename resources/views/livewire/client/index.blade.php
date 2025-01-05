<?php

new #[\Livewire\Attributes\Layout('components.layouts.client')] class extends \Livewire\Volt\Component {

    #[\Livewire\Attributes\Url]
    public $login;

    public function mount(): void
    {
        if ($this->login) {
            $this->dispatch('slide-over.open', ['component' => 'login.login-page']);
        }
    }

};

?>
<div class="container mx-auto p-4 space-y-6">
    <!-- Hero Section -->
    <div class="bg-base-100 rounded-2xl p-6 md:p-8 shadow-sm border border-base-200">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="flex-1 space-y-4">
                <h1 class="text-3xl md:text-4xl font-bold text-base-content">
                    Hoş Geldiniz 👋
                </h1>
                <p class="text-base-content/70 text-lg">
                    7/24 tüm işlemlerinizi güvenle gerçekleştirin.
                </p>
                <div class="flex gap-3">
                    <x-button label="Hemen Başla" class="btn-primary" icon="tabler.arrow-right"/>
                    <x-button label="Daha Fazla" class="btn-ghost" icon="tabler.info-circle"/>
                </div>
            </div>
            <div class="w-full md:w-1/3">
                <img src="{{ asset('landing2.svg') }}" alt="Hero" class="w-full">
            </div>
        </div>
    </div>

    <!-- Hızlı İşlemler -->
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-base-content flex items-center gap-2">
            <i class="text-primary">⚡️</i> Hızlı İşlemler
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Randevu Al -->
            <a href="/randevu-al" class="group">
                <div class="bg-base-100 rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">📅</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-medium text-base-content">Randevu Al</h3>
                            <p class="text-sm text-base-content/70">Hızlıca randevu oluşturun</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Rezervasyon -->
            <a href="/rezervasyon-talep-et" class="group">
                <div class="bg-base-100 rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">📝</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-medium text-base-content">Rezervasyon</h3>
                            <p class="text-sm text-base-content/70">Özel rezervasyon talebi</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Paket Satın Al -->
            <a href="/paket-satin-al" class="group">
                <div class="bg-base-100 rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">🎁</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-medium text-base-content">Paketler</h3>
                            <p class="text-sm text-base-content/70">Avantajlı paketler</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Kullandıkça Kazan -->
            <a href="/kullandikca-kazan" class="group">
                <div class="bg-base-100 rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">💎</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-medium text-base-content">Kazan</h3>
                            <p class="text-sm text-base-content/70">Puan kazanın</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Bildirimler -->
    @if(isset($notifications) && count($notifications))
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-base-content flex items-center gap-2">
                <i class="text-primary">🔔</i> Bildirimler
            </h2>

            <div class="grid gap-4">
                @foreach($notifications as $notification)
                    <x-alert :title="$notification['title']"
                            :description="$notification['description']"
                            :icon="$notification['icon']"
                            :class="$notification['class']">
                        <x-slot:actions>
                            <x-button :label="$notification['action']" />
                        </x-slot:actions>
                    </x-alert>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Carousel -->
    @if(isset($slides) && count($slides))
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-base-content flex items-center gap-2">
                <i class="text-primary">✨</i> Öne Çıkanlar
            </h2>
            <x-carousel :slides="$slides" class="rounded-xl overflow-hidden"/>
        </div>
    @endif
</div>
