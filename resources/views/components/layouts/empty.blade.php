<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}">
    <link rel="mask-icon" href="{{ asset('/favicon.ico') }}" color="#ff2d20">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
<x-main full-width>
    <x-slot:content>
        {{ $slot }}

       {{-- <div class="flex mt-20 justify-center">
            <x-button label="Source code" icon="o-code-bracket" link="/support-us" class="btn-ghost" />
            <x-button label="Built with maryUI" icon="o-heart" link="https://mary-ui.com" class="btn-ghost !text-pink-500" external />
        </div>--}}
    </x-slot:content>
</x-main>

{{-- Toast --}}
<x-toast />
</body>
</html>
