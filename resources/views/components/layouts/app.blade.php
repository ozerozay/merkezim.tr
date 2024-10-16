<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
            <x-button icon="o-bell" class="btn-circle relative">
                <x-badge value="2" class="badge-error absolute -right-2 -top-2" />
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
                @if($user = auth()->user())
                <x-list-item :item="$user" no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:value>
                        {{ auth()->user()->name }}
                    </x-slot:value>
                    <x-slot:sub-value>
                        MARGE GÜZELLİK
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <div class="flex space-x-1">
                            <x-button icon="o-bell" class="btn-circle relative">
                                <x-badge value="2" class="badge-error absolute -right-2 -top-2" />
                            </x-button>
                        <x-theme-toggle class="btn btn-circle" /> 
                        </div>
                    </x-slot:actions>
                </x-list-item>
                <x-menu-separator />
                <x-menu-item title="Ara" @click.stop="$dispatch('mary-search-open')" icon="o-magnifying-glass"
                    badge="Ctrl + M" />

                <x-menu-separator />
                <x-menu-item title="Anasayfa" icon="tabler.home" link="/" />
                <x-menu-item title="Danışan" icon="tabler.user" link="{{ route('admin.clients') }}" />
                <x-menu-item title="Randevu" icon="tabler.calendar-month" link="###" />
                <x-menu-item title="Kasa" icon="tabler.moneybag" link="###" />
                <x-menu-item title="Satış" icon="tabler.credit-card" link="###" />
                <x-menu-item title="Rapor" icon="tabler.graph" link="###" />
                <x-menu-sub title="Ayarlar" icon="o-cog-6-tooth">

                    <x-menu-sub title="Tanımlamalar" icon="o-pencil">

                        <x-menu-item title="Şube" icon="o-arrow-right"
                            link="{{ route('admin.settings.defination.branch') }}" />

                        <x-menu-item title="Kategori" icon="o-arrow-right"
                            link="{{ route('admin.settings.defination.category') }}" />

                        <x-menu-item title="Oda" icon="o-arrow-right"
                            link="{{ route('admin.settings.defination.room') }}" />

                        <x-menu-item title="Kasa" icon="o-arrow-right"
                            link="{{ route('admin.settings.defination.kasa') }}" />

                        <x-menu-item title="Hizmet" icon="o-arrow-right"
                            link="{{ route('admin.settings.defination.service') }}" />

                        <x-menu-item title="Paket" icon="o-arrow-right"
                            link="{{ route('admin.settings.defination.package') }}" />

                        <x-menu-item title="Ürün" icon="o-arrow-right"
                            link="{{ route('admin.settings.defination.product') }}" />

                        <x-menu-item title="Personel" icon="o-arrow-right" link="###" />
                    </x-menu-sub>
                </x-menu-sub>
                <x-menu-item title="Çıkış" icon="tabler.logout" no-wire-navigate link="/logout" />
                @endif
            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- TOAST area --}}
    @persist('toast-spotlight')
    <x-toast />
    <x-spotlight search-text="Danışan, işlem, satış arayın" no-results-text="Bulunamadı." shortcut="ctrl.m" aria-autocomplete="false" />
    @endpersist()

</body>

</html>