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
        <x-app-brand/>

    </x-slot:brand>
    <x-slot:actions>
        <x-button title="Ara" wire:click="$dispatch('mary-search-open')" icon="o-magnifying-glass"
                  badge="Ctrl + M"/>
        <x-button icon="o-bell" class="btn-circle relative">
            <x-badge value="2" class="badge-error absolute -right-2 -top-2"/>
        </x-button>
        <label for="main-drawer" class="lg:hidden me-3">
            <x-icon name="o-bars-3" class="cursor-pointer"/>
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
                                <x-badge value="2" class="badge-error absolute -right-2 -top-2"/>
                            </x-button>
                            <x-theme-toggle class="btn btn-circle"/>
                        </div>
                    </x-slot:actions>
                </x-list-item>
                <x-menu-separator/>
                <x-menu-item title="Ara" @click.stop="$dispatch('mary-search-open')" icon="o-magnifying-glass"
                             badge="Ctrl + M"/>
                <x-menu-item title="Ara" @click="$dispatch('toggle-spotlight')" icon="o-magnifying-glass"
                             badge="Ctrl + M"/>


                <x-menu-separator/>
                @if (1==2)
                    <x-button label="Hi!" class="btn-outline" data-set-theme="cupcake" data-key="mary-theme"/>
                @endif
                <x-menu-item title="Anasayfa" icon="tabler.home" link="{{ route('admin.index') }}"/>
                <x-menu-item title="Danışan" icon="tabler.user" link="{{ route('admin.clients') }}"/>
                <x-menu-item title="Randevu" icon="tabler.calendar-month" link="{{ route('admin.appointment') }}"/>
                <x-menu-item title="Kasa" icon="tabler.moneybag" link="{{ route('admin.kasa') }}"/>
                <x-menu-item title="Ajanda" icon="tabler.moneybag" link="{{ route('admin.agenda') }}"/>
                <x-menu-item title="Talep" icon="tabler.moneybag" link="{{ route('admin.talep') }}"/>
                <x-menu-item title="Satış" icon="tabler.credit-card" link="###"/>
                <x-menu-item title="Rapor" icon="tabler.graph" link="###"/>
                <x-menu-sub title="Ayarlar" icon="o-cog-6-tooth">

                    <x-menu-sub title="Tanımlamalar" icon="o-pencil">

                        <x-menu-item title="Şube" icon="o-arrow-right"
                                     link="{{ route('admin.settings.defination.branch') }}"/>

                        <x-menu-item title="Kategori" icon="o-arrow-right"
                                     link="{{ route('admin.settings.defination.category') }}"/>

                        <x-menu-item title="Oda" icon="o-arrow-right"
                                     link="{{ route('admin.settings.defination.room') }}"/>

                        <x-menu-item title="Kasa" icon="o-arrow-right"
                                     link="{{ route('admin.settings.defination.kasa') }}"/>

                        <x-menu-item title="Hizmet" icon="o-arrow-right"
                                     link="{{ route('admin.settings.defination.service') }}"/>

                        <x-menu-item title="Paket" icon="o-arrow-right"
                                     link="{{ route('admin.settings.defination.package') }}"/>

                        <x-menu-item title="Ürün" icon="o-arrow-right"
                                     link="{{ route('admin.settings.defination.product') }}"/>

                        <x-menu-item title="Personel" icon="o-arrow-right" link="###"/>
                    </x-menu-sub>
                </x-menu-sub>
                <x-menu-item title="Çıkış" icon="tabler.logout" no-wire-navigate link="/logout"/>
            @endif
        </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}

    </x-slot:content>

</x-main>

{{-- TOAST area --}}

@livewire('spotlight-pro')
@persist('toast-spotlight')
<x-toast/>
<x-spotlight search-text="Danışan, işlem, satış arayın" no-results-text="Bulunamadı."
             shortcut="ctrl.m" autocomplete="on"/>
@endpersist()


</body>
<script type="text/javascript">
    function themeToggle() {
        var toggleEl = document.querySelector("[data-toggle-theme]");
        (function (theme = localStorage.getItem("mary-theme")) {
            if (localStorage.getItem("mary-theme")) {
                document.documentElement.setAttribute("data-theme", theme);
                if (toggleEl) {
                    [...document.querySelectorAll("[data-toggle-theme]")].forEach(el => {
                        el.classList.add(toggleEl.getAttribute("data-act-class"))
                    })
                }
            }
        })();
        if (toggleEl) {
            [...document.querySelectorAll("[data-toggle-theme]")].forEach(el => {
                el.addEventListener("click", function () {
                    var themesList = el.getAttribute("data-toggle-theme");
                    if (themesList) {
                        var themesArray = themesList.split(",");
                        if (document.documentElement.getAttribute("data-theme") == themesArray[0]) {
                            if (themesArray.length == 1) {
                                document.documentElement.removeAttribute("data-theme");
                                localStorage.removeItem("mary-theme")
                            } else {
                                document.documentElement.setAttribute("data-theme", themesArray[1]);
                                localStorage.setItem("mary-theme", themesArray[1])
                            }
                        } else {
                            document.documentElement.setAttribute("data-theme", themesArray[0]);
                            localStorage.setItem("mary-theme", themesArray[0])
                        }
                    }
                    [...document.querySelectorAll("[data-toggle-theme]")].forEach(el => {
                        el.classList.toggle(this.getAttribute("data-act-class"))
                    })
                })
            })
        }
    }

    function themeBtn() {
        (function (theme = localStorage.getItem("mary-theme")) {
            if (theme != undefined && theme != "") {
                if (localStorage.getItem("mary-theme") && localStorage.getItem("mary-theme") != "") {
                    document.documentElement.setAttribute("data-theme", theme);
                    var btnEl = document.querySelector("[data-set-theme='" + theme.toString() + "']");
                    if (btnEl) {
                        [...document.querySelectorAll("[data-set-theme]")].forEach(el => {
                            el.classList.remove(el.getAttribute("data-act-class"))
                        });
                        if (btnEl.getAttribute("data-act-class")) {
                            btnEl.classList.add(btnEl.getAttribute("data-act-class"))
                        }
                    }
                } else {
                    var btnEl = document.querySelector("[data-set-theme='']");
                    if (btnEl.getAttribute("data-act-class")) {
                        btnEl.classList.add(btnEl.getAttribute("data-act-class"))
                    }
                }
            }
        })();
        [...document.querySelectorAll("[data-set-theme]")].forEach(el => {
            el.addEventListener("click", function () {
                document.documentElement.setAttribute("data-theme", this.getAttribute("data-set-theme"));
                localStorage.setItem("mary-theme", document.documentElement.getAttribute("data-theme"));
                [...document.querySelectorAll("[data-set-theme]")].forEach(el => {
                    el.classList.remove(el.getAttribute("data-act-class"))
                });
                if (el.getAttribute("data-act-class")) {
                    el.classList.add(el.getAttribute("data-act-class"))
                }
            })
        })
    }

    function themeSelect() {
        (function (theme = localStorage.getItem("mary-theme")) {
            if (localStorage.getItem("mary-theme")) {
                document.documentElement.setAttribute("data-theme", theme);
                var optionToggler = document.querySelector("select[data-choose-theme] [value='" + theme.toString() + "']");
                if (optionToggler) {
                    [...document.querySelectorAll("select[data-choose-theme] [value='" + theme.toString() + "']")].forEach(el => {
                        el.selected = true
                    })
                }
            }
        })();
        if (document.querySelector("select[data-choose-theme]")) {
            [...document.querySelectorAll("select[data-choose-theme]")].forEach(el => {
                el.addEventListener("change", function () {
                    document.documentElement.setAttribute("data-theme", this.value);
                    localStorage.setItem("mary-theme", document.documentElement.getAttribute("data-theme"));
                    [...document.querySelectorAll("select[data-choose-theme] [value='" + localStorage.getItem("mary-theme") + "']")].forEach(el => {
                        el.selected = true
                    })
                })
            })
        }
    }

    function themeChange(attach = true) {
        if (attach === true) {
            document.addEventListener("DOMContentLoaded", function (event) {
                themeToggle();
                themeSelect();
                themeBtn()
            })
        } else {
            themeToggle();
            themeSelect();
            themeBtn()
        }
    }

    if (typeof exports != "undefined") {
        module.exports = {themeChange: themeChange}
    } else {
        // themeChange()
    }</script>

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
