<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $general_settings = \App\Actions\Spotlight\Actions\Settings\GetGeneralSettings::run();
    $site_name = $general_settings->get(\App\Enum\SettingsType::site_name->name);
    $shop_active = $general_settings->get(\App\Enum\SettingsType::shop_active->name);
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . $site_name : $site_name }}</title>
    <meta name="description" content="Marge Güzellik">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" defer>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js" defer>
    </script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-clipboard@2.x.x/dist/alpine-clipboard.js" defer>
    </script>
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200 select-none">


{{-- NAVBAR mobile only --}}
<x-nav sticky class="lg:hidden">
    <x-slot:brand>
        <h1 class="text-xl font-bold text-base-content">
            {{ $site_name }}
        </h1>
    </x-slot:brand>
    <x-slot:actions>
        <div class="flex items-center gap-2">
            <!-- Giriş Yapılmamış Kullanıcı İçin Giriş Butonu -->
            @guest
                <x-button :link="route('login')"
                        class="btn btn-primary btn-sm normal-case">
                    <span class="hidden sm:inline text-xs">Giriş Yap</span>
                    <span class="sm:hidden text-xs">Giriş</span>
                </x-button>
            @else
                <!-- Mobil Menü (Sadece Giriş Yapmış Kullanıcılar İçin) -->
                <label for="main-drawer" class="btn btn-ghost btn-sm lg:hidden">
                    <x-icon name="o-bars-3" class="w-5 h-5" />
                </label>
            @endguest
<!-- Dil Seçimi Butonu -->
<button wire:click="$dispatch('modal.open', {component: 'web.modal.language-modal'})" 
        class="btn btn-ghost btn-sm normal-case gap-2">
    <div class="w-6 h-6 rounded-full bg-base-200 flex items-center justify-center">
        <span class="text-base">{{ app()->getLocale() === 'tr' ? '🇹🇷' : '🇬🇧' }}</span>
    </div>
    <span class="hidden sm:inline text-xs font-medium">
        {{ app()->getLocale() === 'tr' ? 'Türkçe' : 'English' }}
    </span>
    <span class="text-lg">🌐</span>
</button>

            <!-- Tema Değiştirici -->
            <x-theme-toggle class="btn btn-ghost btn-sm p-0 hover:bg-transparent">
                <span class="text-base">🌙</span>
            </x-theme-toggle>
        </div>
    </x-slot:actions>
</x-nav>

{{-- MAIN --}}
<x-main>
    {{-- SIDEBAR --}}
    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 lg:bg-inherit">
        @auth
            <!-- Giriş Yapılmış Kullanıcı -->
            <div class="flex flex-col gap-2 p-3">
                <!-- Profil Kartı -->
                <div class="bg-base-100/50 backdrop-blur-sm rounded-2xl border border-base-200 p-3 {{ auth()->user()->hasRole('admin') ? 'mt-2' : '' }}">
                    <div class="flex items-center justify-between">
                        <!-- Sol: Avatar ve Kullanıcı Bilgileri -->
                        <div class="flex items-center gap-3">
                            <!-- Avatar -->
                            <div class="relative">
                                @if(auth()->user()->avatar)
                                    <img class="w-12 h-12 rounded-xl object-cover" 
                                         src="{{ auth()->user()->avatar }}" 
                                         alt="{{ auth()->user()->name }}">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                        <span class="text-xl text-primary font-medium">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-success rounded-full border-2 border-base-100"></div>
                            </div>
            
                            <!-- Kullanıcı Bilgileri -->
                            <div class="flex flex-col">
                                <span class="font-medium text-base-content">
                                    {{ auth()->user()->name }}
                                </span>
                                <span class="text-sm text-base-content/70">
                                    {{ auth()->user()->client_branch->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            
                @if ($shop_active)
                <!-- Shop Butonu -->
                <a href="{{ route('client.shop.packages') }}" class="flex items-center gap-3 p-2.5 mt-2 rounded-2xl bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 transition-all duration-300 border border-primary/20 shadow-lg shadow-primary/20">
                    <span class="text-xl">✨</span>
                    <div class="flex flex-col">
                        <span class="font-medium text-white">{{ __('client.menu_shop') }}</span>
                        <span class="text-xs text-white/90">Özel hizmet paketleri</span>
                    </div>
                </a>
            @endif
                <!-- Ana Menü -->
                <div class="bg-base-100 rounded-xl border border-base-200">
                    <div class="p-2">
                        <livewire:client.menu.client-auth-menu wire:key="mngfjn-{{Str::random(10)}}" />
                    </div>
                </div>
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
                <!-- Admin Panel Butonu -->
                <a href="{{ route('admin.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl bg-gradient-to-r from-warning to-warning/80 hover:from-warning/90 hover:to-warning/70 transition-all duration-300 border border-warning/20 shadow-lg shadow-warning/20">
                    <span class="text-xl">⚡</span>
                    <div class="flex flex-col">
                        <span class="font-medium text-warning-content">Yönetim Paneli</span>
                        <span class="text-xs text-warning-content/90">Admin paneline hızlı erişim</span>
                    </div>
                </a>
            @endif
               
            </div>
        @else
            <!-- Giriş Yapılmamış Kullanıcı -->
            <div class="flex flex-col gap-2 p-3">
                <!-- Site Başlığı -->
                <div class="bg-base-100 rounded-xl border border-base-200">
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <div>
                                <h1 class="text-xl font-bold text-base-content">
                                    {{ $site_name }}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Giriş Butonu -->
                <div class="bg-base-100 rounded-xl border border-base-200">
                    <div class="p-4">
                        <div class="flex flex-col gap-2">
                            <x-button :link="route('login')"
                                    class="btn btn-primary w-full">
                                Giriş Yap / Üye Ol
                            </x-button>
                        </div>
                    </div>
                </div>

                <!-- Dil ve Tema Seçimi -->
                <div class="bg-base-100 rounded-xl border border-base-200">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <!-- Dil Seçimi -->
                            <button wire:click="$dispatch('modal.open', {component: 'web.modal.language-modal'})" 
                            class="btn btn-ghost btn-sm normal-case gap-2">
                        <div class="w-6 h-6 rounded-full bg-base-200 flex items-center justify-center">
                            <span class="text-base">{{ app()->getLocale() === 'tr' ? '🇹🇷' : '🇬🇧' }}</span>
                        </div>
                        <span class="hidden sm:inline text-xs font-medium">
                            {{ app()->getLocale() === 'tr' ? 'Türkçe' : 'English' }}
                        </span>
                        <span class="text-lg">🌐</span>
                    </button>

                            <!-- Tema Değiştirici -->
                            <x-theme-toggle class="btn btn-ghost btn-sm">
                                <span class="text-base">🌙</span>
                            </x-theme-toggle>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>
<div class="fixed bottom-4 left-1/2 transform -translate-x-1/2" wire:key="cart-buttson-{{ Str::random(10) }}">
    @if ($shop_active)
        @if (request()->is('shop/*'))
        @auth
        <livewire:web.shop.cart-button-page wire:key="djsaxeccr-{{ Str::random(10) }}" />
        @endauth
       
        @else
            <livewire:spotlight.components.shop_button wire:key="djsafweccr" />
        @endif
    @endif
</div>
{{-- TOAST area --}}
@persist('toast-spotlight')
@livewire('slide-over-pro')
@livewire('modal-pro')
<x-toast />
@endpersist()
<x-theme-toggle class="hidden" />

</body>
<script>
    document.addEventListener("livewire:init", () => {
        Livewire.hook("request", ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419) {
                    // Sayfayı yenile
                    location.reload();

                    // Varsayılan davranışı engelle
                    preventDefault();
                }
            });
        });
    });

</script>
<script type="text/javascript">
    /*document.addEventListener("contextmenu", function(e) {
        e.preventDefault();
    }, false);*/
</script>
<script type="text/javascript">
    window.addEventListener("message", function(event) {
        if (event.data.type === "triggerLivewireEvent") {
            Livewire.dispatch("checkout-result-changed", [event.data.data, event.data.status, event.data.message]);
        }
    });
</script>
<script type="text/javascript">
    function otpSend(num) {
        const milliseconds = num * 1000; //60 seconds
        const currentDate = Date.now() + milliseconds;
        var countDownTime = new Date(currentDate).getTime();
        let interval;
        return {
            countDown: milliseconds,
            countDownTimer: new Date(currentDate).getTime(),
            intervalID: null,
            init() {
                if (!this.intervalID) {
                    this.intervalID = setInterval(() => {
                        this.countDown = this.countDownTimer - new Date().getTime();
                    }, 1000);
                }
            },
            getTime() {
                if (this.countDown < 0) {
                    this.clearTimer();
                }
                return this.countDown;
            },
            formatTime(num) {
                var date = new Date(num);
                return new Date(this.countDown).toLocaleTimeString(navigator.language, {
                    minute: "2-digit",
                    second: "2-digit"
                });
            },
            clearTimer() {
                clearInterval(this.intervalID);
            }
        };
    }
</script>
<style type="text/css">
    table {
        @apply !static
    }

    table details.dropdown {
        @apply !static
    }

    table {
        @apply !static
    }

    .popover {
        z-index: 99999 !;
    }

    table details.popover {
        @apply !static
    }
</style>

</html>
