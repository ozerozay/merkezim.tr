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
        MARGE GÜZELLİK
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
            <div class="flex">
                <p class="text-2xl font-bold">MARGE GÜZELLİK</p>
                <x-theme-toggle class="btn btn-circle"/>
            </div>

            <x-menu-separator/>
            @if (1==2)
                <x-button label="Hi!" class="btn-outline" data-set-theme="cupcake" data-key="mary-theme"/>
            @endif

            <x-menu-item title="Anasayfa" icon="tabler.home" link="/"/>
            <x-menu-sub title="Hizmetlerimiz" icon="o-home">
                <x-menu-item title="LAZER EPİLASYON" icon="o-user"/>
                <x-menu-item title="CİLT BAKIMI" icon="o-folder"/>
                <x-menu-item title="BÖLGESEL ZAYIFLAMA" icon="o-folder"/>
            </x-menu-sub>
            <x-menu-item title="Şubelerimiz" icon="tabler.calendar-month" link="###"/>
            <x-menu-item title="İletişim" icon="tabler.calendar-month" link="###"/>
            <x-menu-separator/>
            <x-menu-item title="ONLİNE İŞLEM MERKEZİ" icon="tabler.mood-check"/>
            <x-menu-separator/>
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
