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

    public function setTheme($theme)
    {
        $this->dispatch('theme-changed', theme: $theme);
    }

};

?>
<div class="container mx-auto p-1 space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-end items-center gap-4">
        <!-- Dil Seçici -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost gap-2">
                <span>{{ app()->getLocale() === 'tr' ? '🇹🇷 Türkçe' : 'English' }}</span>
                <span class="text-xs">🔽</span>
            </label>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-200 rounded-box w-40">
                <li>
                    <a href="{{ route('client.index') }}?lang=tr" class="flex items-center gap-2 {{ app()->getLocale() === 'tr' ? 'active' : '' }}">
                        <span class="text-xl">🇹🇷</span>
                        <span>Türkçe</span>
                        {{ app()->getLocale() === 'tr' ? '✅' : '' }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('client.index') }}?lang=en" class="flex items-center gap-2 {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                        <span>English</span>
                        {{ app()->getLocale() === 'en' ? '✅' : '' }}
                    </a>
                </li>
            </ul>
        </div>

        <!-- Dark/Light Mode Toggle -->
        <button class="btn btn-ghost btn-circle" onclick="toggleDarkMode()">
            <span class="light-mode-icon text-xl">☀️</span>
            <span class="dark-mode-icon hidden text-xl">🌙</span>
        </button>

        <!-- Tema Seçici -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost gap-2">
                <span class="text-xl">🎨</span>
                <span>Tema</span>
            </label>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-200 rounded-box w-52">
                <li class="menu-title">Açık Temalar</li>
                <li>
                    <button wire:click="setTheme('morTema')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#FF69B4]"></span>
                        Mor
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('lavanderTema')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#9B7EDE]"></span>
                        Lavanta
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('mintTema')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#98FF98]"></span>
                        Nane
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('peachTema')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#FFDAB9]"></span>
                        Şeftali
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('skyTema')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#87CEEB]"></span>
                        Gökyüzü
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('roseTema')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#FFB6C1]"></span>
                        Gül
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('sunsetTema')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#FFA07A]"></span>
                        Günbatımı
                    </button>
                </li>
                <div class="divider my-0">Koyu Temalar</div>
                <li>
                    <button wire:click="setTheme('morTemaDark')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#FF69B4] ring-1 ring-white/20"></span>
                        Mor (Koyu)
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('lavanderTemaDark')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#9B7EDE] ring-1 ring-white/20"></span>
                        Lavanta (Koyu)
                    </button>
                </li>
                <li>
                    <button wire:click="setTheme('mintTemaDark')" class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#98FF98] ring-1 ring-white/20"></span>
                        Nane (Koyu)
                    </button>
                </li>
            </ul>
        </div>

        <!-- Client Menu -->
        <div class="dropdown dropdown-end">
            <!-- ... mevcut client menu kodu ... -->
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-base-100/95 backdrop-blur-sm rounded-2xl p-4 md:p-8 shadow-lg border border-base-200">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1 space-y-6">
                <div class="space-y-2">
                    <h1 class="text-4xl md:text-5xl font-bold text-base-content">
                        Marge <span class="text-primary">Güzellik</span> ✨
                    </h1>
                    <p class="text-base-content/70 text-lg">
                        Profesyonel hizmetlerimizle kendinizi özel hissedin. 7/24 online randevu ve özel fırsatlar.
                    </p>
                </div>

                <div class="flex flex-wrap gap-4">
                    <button class="btn btn-primary btn-lg gap-2">
                        <span>Rezervasyon Yap</span>
                        <span class="text-xl">📅</span>
                    </button>
                </div>

                <!-- İstatistikler -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4">
                    <div class="text-center p-4 bg-base-200/50 rounded-xl">
                        <div class="text-2xl font-bold text-primary">5000+</div>
                        <div class="text-sm text-base-content/70">Mutlu Müşteri</div>
                    </div>
                    <div class="text-center p-4 bg-base-200/50 rounded-xl">
                        <div class="text-2xl font-bold text-primary">15+</div>
                        <div class="text-sm text-base-content/70">Uzman Kadro</div>
                    </div>
                    <div class="text-center p-4 bg-base-200/50 rounded-xl hidden sm:block">
                        <div class="text-2xl font-bold text-primary">12+</div>
                        <div class="text-sm text-base-content/70">Yıllık Deneyim</div>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-2/5">
                <img src="{{ asset('landing2.svg') }}" alt="Hero" class="w-full drop-shadow-xl">
            </div>
        </div>
    </div>

    <!-- Hızlı İşlemler -->
    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-base-content flex items-center gap-2">
            <span class="text-primary">⚡️</span> Hızlı İşlemler
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Randevu Al -->
            <a href="/randevu-al" class="group">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">📅</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-base-content">Randevu Al</h3>
                            <p class="text-sm text-base-content/70">Online Randevu</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Paketler -->
            <a href="/paket-satin-al" class="group">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">💝</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-base-content">Paketler</h3>
                            <p class="text-sm text-base-content/70">Özel Fırsatlar</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Hizmetler -->
            <a href="/hizmetlerimiz" class="group">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">💆‍♀️</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-base-content">Hizmetler</h3>
                            <p class="text-sm text-base-content/70">Tüm Hizmetler</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Kampanyalar -->
            <a href="/kampanyalar" class="group">
                <div class="bg-base-100/95 backdrop-blur-sm rounded-xl p-6 border border-base-200 hover:border-primary hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="text-2xl">🎁</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-base-content">Kampanyalar</h3>
                            <p class="text-sm text-base-content/70">Özel İndirimler</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Bildirimler -->
    @if(isset($notifications) && count($notifications))
        <div class="space-y-4">
            <h2 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <span class="text-primary">🔔</span> Bildirimler
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
            <h2 class="text-2xl font-bold text-base-content flex items-center gap-2">
                <span class="text-primary">✨</span> Öne Çıkanlar
            </h2>
            <x-carousel :slides="$slides" class="rounded-xl overflow-hidden shadow-lg"/>
        </div>
    @endif
</div>
