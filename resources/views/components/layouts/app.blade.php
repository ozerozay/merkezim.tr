<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="morTema" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"
            type="text/javascript">
    </script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">

@if (1==2)
    {{-- NAVBAR mobile only --}}
    <x-nav sticky>
        <x-slot:brand>
            <x-app-brand />

        </x-slot:brand>
        <x-slot:actions>
            <livewire:spotlight.components.notification_button wire:key="asdngccc" />


        </x-slot:actions>

    </x-nav>
@endif
{{-- MAIN --}}
<x-main>
    {{-- SIDEBAR --}}
    @if (1 == 2)
        <x-slot:sidebar drawer="main-drawer" collapse-text="Küçült" collapsible
                        class="bg-base-100 lg:bg-inherit">
            {{-- MENU --}}

            <x-menu class="mt-2" activate-by-route>
                {{-- User --}}
                @if ($user = auth()->user())
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
                    <x-menu-item title="Ara" @click.stop="$dispatch('mary-search-open')"
                                 icon="o-magnifying-glass" badge="Ctrl + M" />
                    <x-menu-item title="Ara" @click="$dispatch('toggle-spotlight')" icon="o-magnifying-glass"
                                 badge="Ctrl + M" />
                    <x-menu-item title="Ara" @click="$dispatch('spotlight.toggle')" icon="o-magnifying-glass"
                                 badge="Ctrl + M" />
                    <x-button icon="tabler.wand" wire:click="$dispatch('spotlight.toggle')"
                              class="btn-primary btn-circle btn-lg" />

                    <x-menu-separator />
                    @if (1 == 2)
                        <x-button label="Hi!" class="btn-outline" data-set-theme="cupcake"
                                  data-key="mary-theme" />
                    @endif
                    <x-menu-item title="Anasayfa" icon="tabler.home" link="{{ route('admin.index') }}" />
                    <x-menu-item title="Danışan" icon="tabler.user" link="{{ route('admin.clients') }}" />
                    <x-menu-item title="Randevu" icon="tabler.calendar-month"
                                 link="{{ route('admin.appointment') }}" />
                    <x-menu-item title="Kasa" icon="tabler.moneybag" link="{{ route('admin.kasa') }}" />
                    <x-menu-item title="Ajanda" icon="tabler.moneybag" link="{{ route('admin.agenda') }}" />
                    <x-menu-item title="Talep" icon="tabler.moneybag" link="{{ route('admin.talep') }}" />
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
    @endif

    {{-- The `$slot` goes here --}}
    <x-slot:content>

        {{ $slot }}
        <x-theme-toggle class="btn btn-circle hidden" />

    </x-slot:content>


</x-main>

{{-- TOAST area --}}

@persist('toast-spotlight')
@livewire('modal-pro')
@livewire('slide-over-pro')
@livewire('spotlight-pro')
@livewire('spotlight.components.spotlight_button', [], key(123))
<x-toast />
@endpersist()
<x-theme-toggle class="hidden" />

<livewire:merkezim-spotlight />

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
