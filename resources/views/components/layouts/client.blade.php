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
        <x-app-brand/>
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
    <x-slot:sidebar drawer="main-drawer" collapse-text="Küçült" collapsible class="bg-base-100 p-3 lg:bg-inherit">

        <x-menu class="mt-2 " activate-by-route>


            @if (1 == 2)
                <x-button label="Hi!" class="btn-outline" data-set-theme="cupcake" data-key="mary-theme"/>
            @endif
            @if ($user = auth()->user())

                <x-list-item :item="$user" value="name" sub-value="client_branch.name" no-separator no-hover
                             class="-mx-2 !-my-2 rounded">
                    <x-slot:value class="font-semibold text-xl overflow-hidden text-ellipsis whitespace-nowrap">
                        {{ auth()->user()->name }}
                    </x-slot:value>
                    <x-slot:actions>
                        <x-dropdown>
                            <x-slot:trigger>
                                <x-button icon="o-cog-6-tooth"
                                          class="btn-circle text-white transition-colors duration-200"/>
                            </x-slot:trigger>
                            <x-menu-item icon="tabler.user-circle" label="Bilgilerini Düzenle"
                                         @click.stop="$dispatch('mary-toggle-theme')"/>
                            <x-menu-item icon="tabler.camera-plus" label="Profil Fotoğrafı Yükle"
                                         @click.stop="$dispatch('mary-toggle-theme')"/>
                            <x-menu-item icon="tabler.moon" label="Koyu Mod"
                                         @click.stop="$dispatch('mary-toggle-theme')"/>
                            <x-menu-item icon="o-power" label="Çıkış" link="{{ route('logout') }}" no-wire-navigate/>
                        </x-dropdown>
                    </x-slot:actions>
                </x-list-item>

                @if (1==2)
                    <x-menu-item
                        icon="tabler.user-circle"
                        class="bg-gray-900 text-white hover:bg-gray-700 transition-all duration-300 p-4 rounded-lg mb-4 shadow-lg"
                    >
                        <x-slot:title>
                            <div class="flex items-center justify-between w-full">
                                <!-- Avatar -->
                                <div class="flex items-center space-x-4">
                                    <img class="w-14 h-14 rounded-full border-4 border-gray-700 dark:border-gray-500"
                                         src="https://i.pravatar.cc/150?img=1" alt="Avatar">

                                    <div>
                                        <!-- Kullanıcı Adı ve Şube Adı -->
                                        <span class="font-semibold text-xl text-gray-200 dark:text-white">
                        {{ Str::substr(auth()->user()->name, 0, 20) }}
                    </span>
                                        <span class="text-sm text-gray-400 dark:text-gray-300">
                        {{ auth()->user()->client_branch->name }}
                    </span>
                                    </div>
                                </div>

                                <!-- Ayarlar Butonu -->
                                <button
                                    class="btn btn-sm bg-transparent border-2 border-gray-400 text-gray-200 hover:bg-gray-600 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 rounded-md">
                                    Ayarlar
                                </button>
                            </div>
                        </x-slot:title>
                    </x-menu-item>
                @endif
                <x-menu-item title="Anasayfa"
                             class="text-white bg-cyan-500 hover:bg-cyan-700 p-3 rounded-lg transition-all duration-300"
                             icon="tabler.home" link="/"/>
                <x-menu-item title="Bize Ulaşın"
                             class="text-white bg-green-500 hover:bg-green-700 p-3 rounded-lg transition-all duration-300"
                             icon="tabler.help"/>
                <livewire:client.menu.client-auth-menu wire:key="mngfjn-{{Str::random(10)}}"/>
                @if (1==2)
                    <x-menu-item
                        title="{{ __('client.menu_profil') }}"
                        icon="tabler.user-circle"
                        link="{{ route('login') }}"
                        class="bg-gray-800 text-white hover:bg-gray-700 transition-all duration-300 p-3 rounded-lg mb-2"
                    >
                    </x-menu-item>
                @endif
                <x-menu-item
                    title="{{ __('client.menu_logout') }}"
                    icon="tabler.logout"
                    link="{{ route('logout') }}"
                    class="bg-red-600 text-white hover:bg-red-500 transition-all duration-300 p-3 rounded-lg"
                    no-wire-navigate
                >
                </x-menu-item>

            @else
                {{-- User --}}
                <div class="flex items-center">
                    <p class="text-2xl font-bold flex-1">
                        {{ $site_name }}
                    </p>
                    <x-theme-toggle class="btn btn-circle ml-auto"/>
                </div>
                <x-hr/>
                <livewire:spotlight.components.login_button wire:key="lg-xks-{{ Str::random(10) }}"/>
                <x-menu-separator/>
            @endif
            <div>
                @if (1==2)
                    <x-menu-separator/>
                    <x-menu-sub title="Hizmetlerimiz"
                                class="text-white bg-orange-500 hover:bg-orange-700 p-3 rounded-lg transition-all duration-300"
                                icon="tabler.heart">
                        <x-menu-item title="LAZER EPİLASYON"
                                     class="text-white bg-orange-400 hover:bg-orange-600 p-2 rounded-lg transition-all duration-300"
                                     icon="tabler.arrow-right" link="{{ route('client.service') }}"/>
                        <x-menu-item title="CİLT BAKIMI"
                                     class="text-white bg-orange-400 hover:bg-orange-600 p-2 rounded-lg transition-all duration-300"
                                     icon="tabler.arrow-right" link="{{ route('client.service') }}"/>
                        <x-menu-item title="BÖLGESEL ZAYIFLAMA"
                                     class="text-white bg-orange-400 hover:bg-orange-600 p-2 rounded-lg transition-all duration-300"
                                     icon="tabler.arrow-right" link="{{ route('client.service') }}"/>
                    </x-menu-sub>
                    <x-menu-separator/>
                @endif
            </div>


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
<x-theme-toggle class="hidden"/>
@endpersist()


</body>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({fail}) => {
            fail(({status, preventDefault}) => {
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
