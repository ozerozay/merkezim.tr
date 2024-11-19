<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cupcake">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

{{-- NAVBAR mobile only --}}
<x-nav sticky class="lg:hidden">
    <x-slot:brand>
        <p class="text-2xl font-bold">MARGE GÜZELLİK</p>
    </x-slot:brand>
    <x-slot:actions>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="o-bars-3" class="cursor-pointer"/>
        </label>
        <x-button icon="tabler.shopping-bag" class="btn-circle relative">
            <x-badge value="2" class="badge-error absolute -right-2 -top-2"/>
        </x-button>

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
                <p class="text-2xl font-bold flex-1">MARGE</p>
                <x-theme-toggle class="btn btn-circle ml-auto"/>
            </div>


            <x-menu-separator/>
            @if (1==2)
                <x-button label="Hi!" class="btn-outline" data-set-theme="cupcake" data-key="mary-theme"/>
            @endif
            @if ($user = auth()->user())
                <x-list-item :item="$user" no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:value>
                        {{ auth()->user()->name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        952-315-346
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <div class="flex space-x-1">
                            <x-button icon="tabler.logout" class="btn-circle" link="{{ route('logout') }}"/>
                        </div>
                    </x-slot:actions>
                </x-list-item>
                <x-menu-item title="Seanslarım" icon="tabler.mood-check" link="{{ route('client.profil.seans') }}"/>
                <x-menu-item title="Randevu" icon="tabler.calendar" link="{{ route('client.profil.appointment') }}"
                             badge="1" badge-classes="float-right !badge-warning"/>
                <x-menu-item title="Taksitlerim" icon="tabler.file-invoice" link="{{ route('client.profil.taksits') }}"
                             badge="1" badge-classes="float-right !badge-error"/>
                <x-menu-item title="Tekliflerim" icon="tabler.confetti" link="{{ route('client.profil.offers') }}"
                             badge="1" badge-classes="float-right !badge-success"/>
                <x-menu-item title="Kuponlarım" icon="tabler.gift-card" link="{{ route('client.profil.coupons') }}"
                             badge="1" badge-classes="float-right !badge-success"/>
                <x-menu-item title="Referans" icon="tabler.user-plus" link="{{ route('login') }}"/>
                <x-menu-item title="Paket" icon="tabler.circle-plus" link="{{ route('login') }}"/>
                <x-menu-item title="Kullan - Kazan" icon="tabler.heart" link="{{ route('login') }}"/>
                <x-menu-item title="Faturalarım" icon="tabler.file-invoice" link="{{ route('login') }}"/>
                <x-menu-item title="Profilim" icon="tabler.user-circle" link="{{ route('login') }}"/>
                <x-menu-separator/>

            @else
                <x-menu-item title="ONLİNE İŞLEM MERKEZİ" icon="tabler.mood-check" link="{{ route('login') }}"/>
                <x-menu-separator/>
            @endif
            <x-menu-item title="Anasayfa" link="/" icon="tabler.home"/>
            <x-menu-sub title="Hizmetlerimiz" icon="tabler.heart">
                <x-menu-item title="LAZER EPİLASYON" icon="tabler.arrow-right" link="{{route('client.service')}}"/>
                <x-menu-item title="CİLT BAKIMI" icon="tabler.arrow-right" link="{{route('client.service')}}"/>
                <x-menu-item title="BÖLGESEL ZAYIFLAMA" icon="tabler.arrow-right" link="{{route('client.service')}}"/>
            </x-menu-sub>
            <x-menu-sub title="Bize Ulaşın" icon="tabler.help" link="{{route('client.contact')}}">
                <x-menu-item title="İletişim Formu" icon="tabler.help-octagon" link="{{route('client.contact')}}"/>
                <x-menu-item title="Yol Tarifi" icon="tabler.map-pin" link="{{route('client.location')}}"/>
            </x-menu-sub>
            <x-menu-separator/>


            <x-menu-item title="0850 241 1010" icon="tabler.phone" external
                         link="https://www.instagram.com/margeguzellik"/>
            <x-menu-item title="Instagram" icon="tabler.brand-instagram" external
                         link="https://www.instagram.com/margeguzellik"/>
            <x-menu-item title="Whatsapp" icon="tabler.brand-whatsapp" external
                         link="https://wa.me/905056277636"/>
        </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}
    </x-slot:content>
</x-main>

{{-- TOAST area --}}
@persist('toast-spotlight')
<x-toast/>
@endpersist()

</body>
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
