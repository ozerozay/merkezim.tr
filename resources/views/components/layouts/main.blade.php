<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"></script>

</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky>
 
        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
 
                <x-app-brand />
        </x-slot:brand>
 
        {{-- Right side actions --}}
        <x-slot:actions>
            @if($user = auth()->user())
            <x-button label="Mesajlar" icon="o-envelope" link="###" class="btn-ghost btn-sm" responsive />
            <x-button label="Bildirimler" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive />
            <x-button label="Profil" icon="o-user-circle" link="###" class="btn-ghost btn-sm" responsive />
            @endif
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />

    <x-spotlight />  
</body>
</html>
