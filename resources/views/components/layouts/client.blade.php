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
        <x-app-brand />
    </x-slot:brand>
    <x-slot:actions>

        @if (1 == 2)
            <x-button icon="tabler.shopping-bag" class="btn-circle relative">
                <x-badge value="0" class="badge-error absolute -right-2 -top-2" />
            </x-button>
        @endif
        <x-button icon="tabler.bell" class="btn-circle relative">
            <x-badge value="0" class="badge-error absolute -right-2 -top-2" />
        </x-button>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="o-bars-3" class="cursor-pointer rounded-full" />
        </label>

    </x-slot:actions>
</x-nav>

{{-- MAIN --}}
<x-main>
    {{-- SIDEBAR --}}
    <x-slot:sidebar drawer="main-drawer" class="bg-base-100 p-3 lg:bg-inherit">

        <x-menu class="mt-2" activate-by-route>
            @if ($user = auth()->user())
                <livewire:client.menu.client-auth-menu wire:key="mngfjn-{{Str::random(10)}}" />
            @else
                {{-- Giriş Yapılmamış Kullanıcı --}}
                <div class="flex flex-col">
                    <!-- Site Başlığı -->
                    <div class="bg-base-100/50 backdrop-blur-sm rounded-2xl border border-base-200 p-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xl font-bold text-base-content">
                                {{ $site_name }}
                            </p>
                        </div>
                    </div>

                    <!-- Marge Shop - Canlı Tasarım -->
                    @if ($shop_active)
                        <a href="{{ route('client.shop.packages') }}" class="flex items-center gap-2 p-2 mt-2 rounded-xl bg-gradient-to-r from-primary/90 to-primary hover:from-primary hover:to-primary/90 transition-all duration-300 transform hover:scale-[1.02] shadow-md shadow-primary/20 group-hover:shadow-lg group-hover:shadow-primary/30">
                            <span class="text-lg text-white">✨</span>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-white">{{ __('client.menu_shop') }}</span>
                                <span class="text-[11px] text-white/90">Özel hizmet paketleri</span>
                            </div>
                            <div class="ml-auto text-white/90">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </a>
                    @endif

                    <!-- Giriş Butonu -->
                    <div class="bg-base-100 p-2 mt-2 rounded-2xl border border-base-200">
                        <livewire:spotlight.components.login_button wire:key="lg-xks-{{ Str::random(10) }}" />
                    </div>
                </div>
            @endif
        </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>

</x-main>
<div class="fixed bottom-4 left-1/2 transform -translate-x-1/2" wire:key="cart-buttson-{{ Str::random(10) }}">
    @if ($shop_active)
        @if (request()->is('shop/*'))
            <livewire:web.shop.cart-button-page wire:key="djsaxeccr-{{ Str::random(10) }}" />
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
