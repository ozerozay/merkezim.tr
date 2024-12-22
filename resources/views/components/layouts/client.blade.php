<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cupcake">
@php
    $general_settings = \App\Actions\Spotlight\Actions\Settings\GetGeneralSettings::run();
    $site_name = $general_settings->get(\App\Enum\SettingsType::site_name->name);
    $shop_active = $general_settings->get(\App\Enum\SettingsType::shop_active->name);
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . $site_name : $site_name }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js">
    </script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-clipboard@2.x.x/dist/alpine-clipboard.js" defer>
    </script>
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200 select-none">


{{-- NAVBAR mobile only --}}
<x-nav sticky class="lg:hidden">
    <x-slot:brand>
        <p class="text-2xl font-bold">
            {{ $site_name }}
        </p>
    </x-slot:brand>
    <x-slot:actions>

        @if (1 == 2)
            <x-button icon="tabler.shopping-bag" class="btn-circle relative">
                <x-badge value="0" class="badge-error absolute -right-2 -top-2"/>
            </x-button>
        @endif
        <x-button icon="tabler.bell" class="btn-circle relative">
            <x-badge value="0" class="badge-error absolute -right-2 -top-2"/>
        </x-button>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="o-bars-3" class="cursor-pointer rounded-full"/>
        </label>

    </x-slot:actions>
</x-nav>

{{-- MAIN --}}
<x-main>
    {{-- SIDEBAR --}}
    <x-slot:sidebar drawer="main-drawer" collapse-text="Küçült" collapsible class="bg-base-100 lg:bg-inherit">
        {{-- MENU --}}
        <x-menu class="mt-2" activate-by-route>
            {{-- User --}}
            <div class="flex items-center">
                <p class="text-2xl font-bold flex-1">
                    {{ $site_name }}
                </p>
                <x-theme-toggle class="btn btn-circle ml-auto"/>
            </div>


            <x-menu-separator/>
            @if (1 == 2)
                <x-button label="Hi!" class="btn-outline" data-set-theme="cupcake" data-key="mary-theme"/>
            @endif
            @if ($user = auth()->user())
                <x-list-item :item="$user" no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:value>
                        {{ auth()->user()->name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        {{ auth()->user()->unique_id }}
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-button icon="tabler.bell" class="btn-circle" link="{{ route('logout') }}"/>
                    </x-slot:actions>
                </x-list-item>
                @php
                    $allSettings = \App\Actions\Spotlight\Actions\Settings\GetAllSettingsAction::run();
                @endphp
                @if ($shop_active)
                    <x-menu-item title="{{ __('client.menu_shop') }}" class="underline font-bold"
                                 icon="tabler.circle-plus" link="{{ route('client.shop.packages') }}"/>
                @endif

                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_seans->name))
                    <x-menu-item title="{{ __('client.menu_seans') }}" icon="tabler.mood-check"
                                 link="{{ route('client.profil.seans') }}"/>
                @endif
                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_appointment->name))
                    <x-menu-item title="{{ __('client.menu_appointment') }}" icon="tabler.calendar"
                                 link="{{ route('client.profil.appointment') }}" badge="1"
                                 badge-classes="float-right !badge-warning"/>
                @endif
                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_taksit->name))
                    <x-menu-item title="{{ __('client.menu_payments') }}" icon="tabler.file-invoice"
                                 link="{{ route('client.profil.taksit') }}" badge="1"
                                 badge-classes="float-right !badge-error"/>
                @endif
                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_offer->name))
                    <x-menu-item title="{{ __('client.menu_offer') }}" icon="tabler.confetti"
                                 link="{{ route('client.profil.offer') }}" badge="1"
                                 badge-classes="float-right !badge-success"/>
                @endif
                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_coupon->name))
                    <x-menu-item title="{{ __('client.menu_coupon') }}" icon="tabler.gift-card"
                                 link="{{ route('client.profil.coupon') }}" badge="1"
                                 badge-classes="float-right !badge-success"/>
                @endif
                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_referans->name))
                    <x-menu-item title="{{ __('client.menu_referans') }}" icon="tabler.user-plus"
                                 link="{{ route('client.profil.invite') }}"/>
                @endif

                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_earn->name))
                    <x-menu-item title="{{ __('client.menu_earn') }}" icon="tabler.heart"
                                 link="{{ route('login') }}"/>
                @endif
                @if ($allSettings->contains(\App\Enum\SettingsType::client_page_fatura->name))
                    <x-menu-item title="{{ __('client.menu_invoice') }}" icon="tabler.file-invoice"
                                 link="{{ route('login') }}"/>
                @endif
                <x-menu-item title="{{ __('client.menu_profil') }}" icon="tabler.user-circle"
                             link="{{ route('login') }}"/>
                <x-menu-item title="{{ __('client.menu_logout') }}" icon="tabler.logout"
                             link="{{ route('logout') }}"/>
                <x-menu-separator/>
            @else
                <livewire:spotlight.components.login_button wire:key="lg-xks-{{ Str::random(10) }}"/>
                <x-menu-separator/>
            @endif
            <x-menu-item title="Anasayfa" link="/" icon="tabler.home"/>
            <x-menu-sub title="Hizmetlerimiz" icon="tabler.heart">
                <x-menu-item title="LAZER EPİLASYON" icon="tabler.arrow-right"
                             link="{{ route('client.service') }}"/>
                <x-menu-item title="CİLT BAKIMI" icon="tabler.arrow-right" link="{{ route('client.service') }}"/>
                <x-menu-item title="BÖLGESEL ZAYIFLAMA" icon="tabler.arrow-right"
                             link="{{ route('client.service') }}"/>
            </x-menu-sub>
            <x-menu-sub title="Bize Ulaşın" icon="tabler.help" link="{{ route('client.contact') }}">
                <x-menu-item title="İletişim Formu" icon="tabler.help-octagon"
                             link="{{ route('client.contact') }}"/>
                <x-menu-item title="Yol Tarifi" icon="tabler.map-pin" link="{{ route('client.location') }}"/>
            </x-menu-sub>
            <x-menu-separator/>

            @if (1 == 2)
                <x-menu-item title="0850 241 1010" icon="tabler.phone" external
                             link="https://www.instagram.com/margeguzellik"/>
                <x-menu-item title="Instagram" icon="tabler.brand-instagram" external
                             link="https://www.instagram.com/margeguzellik"/>
                <x-menu-item title="Whatsapp" icon="tabler.brand-whatsapp" external
                             link="https://wa.me/905056277636"/>
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
            <livewire:web.shop.cart-button-page wire:key="djsaxeccr-{{ Str::random(10) }}"/>
        @else
            <livewire:spotlight.components.shop_button wire:key="djsafweccr"/>
        @endif
    @endif
</div>
{{-- TOAST area --}}
@persist('toast-spotlight')
@livewire('slide-over-pro')
@livewire('modal-pro')
<x-toast/>
@endpersist()


</body>
<script type="text/javascript">
    /*document.addEventListener("contextmenu", function(e) {
        e.preventDefault();
    }, false);*/
</script>
<script type="text/javascript">
    window.addEventListener('message', function (event) {
        if (event.data.type === 'triggerLivewireEvent') {
            Livewire.dispatch('checkout-result-changed', [event.data.data, event.data.status, event.data.message]);
        }
    });
</script>
<script type="text/javascript">
    function otpSend(num) {
        const milliseconds = num * 1000 //60 seconds
        const currentDate = Date.now() + milliseconds
        var countDownTime = new Date(currentDate).getTime()
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
                    this.clearTimer()
                }
                return this.countDown;
            },
            formatTime(num) {
                var date = new Date(num);
                return new Date(this.countDown).toLocaleTimeString(navigator.language, {
                    minute: '2-digit',
                    second: '2-digit'
                });
            },
            clearTimer() {
                clearInterval(this.intervalID);
            }
        }
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
