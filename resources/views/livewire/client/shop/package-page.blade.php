<div>
    <x-header title="{{ __('client.menu_shop') }}" subtitle="{{ __('client.page_shop_package_subtitle') }}" separator
        progress-indicator>
        <x-slot:actions>
            <div class="flex flex-wrap gap-5">
                <div class="w-full lg:w-auto">
                    <x-input placeholder="{{ __('client.search') }}..." wire:model.live.debounce.500ms="search"
                        icon="o-magnifying-glass" class="border-neutral text-sm" />
                </div>

                <x-dropdown>
                    <x-slot:trigger>
                        <x-button label="Şube" icon-right="o-chevron-down" :badge="count($gender_id) ?: null" class="btn-outline" />
                    </x-slot:trigger>

                    <x-menu-item title="Sıfırla" icon="o-x-mark" @click="$wire.set('gender_id', [])" />

                    <x-menu-separator />

                    @foreach ($genders as $gender)
                        <x-menu-item @click.stop="">
                            <x-checkbox label="{{ $gender['name'] }}" value="{{ $gender['id'] }}"
                                wire:model.live="gender_id" />
                        </x-menu-item>
                    @endforeach
                </x-dropdown>

                <x-dropdown>
                    <x-slot:trigger>
                        <x-button label="Cinsiyet" icon-right="o-chevron-down" :badge="count($gender_id) ?: null" class="btn-outline" />
                    </x-slot:trigger>

                    <x-menu-item title="Sıfırla" icon="o-x-mark" @click="$wire.set('gender_id', [])" />

                    <x-menu-separator />

                    @foreach ($genders as $gender)
                        <x-menu-item @click.stop="">
                            <x-checkbox label="{{ $gender['name'] }}" value="{{ $gender['id'] }}"
                                wire:model.live="gender_id" />
                        </x-menu-item>
                    @endforeach
                </x-dropdown>

                <x-dropdown label="Kategori" class="btn-outline">
                    <x-slot:trigger>
                        <x-button label="Kategori" icon-right="o-chevron-down" :badge="count($categories_id) ?: null" class="btn-outline" />
                    </x-slot:trigger>

                    <x-menu-item title="Sıfırla" icon="o-x-mark" @click="$wire.set('categories_id', [])" />

                    <x-menu-separator />

                    @foreach ($categories as $category)
                        <x-menu-item @click.stop="">
                            <x-checkbox :label="$category->name" :value="$category->id" wire:model.live="categories_id" />
                        </x-menu-item>
                    @endforeach
                </x-dropdown>

                {{-- Clear filters --}}
                @if ($hasFilters)
                    <x-button label="Sıfırla" icon="o-x-mark" wire:click="clearFilters" />
                @endif
            </div>
        </x-slot:actions>
    </x-header>
    <div class="grid md:grid-cols-2  lg:grid-cols-4 gap-4 mt-5">

        <x-card shadow class="card w-full bg-base-100 shadow-xl cursor-pointer border border-pink-300"
            wire:click="handleClick">
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                8 SEANS CİLT BAKIMI
            </x-slot:title>

            <x-slot:subtitle>
                @price(150000)
            </x-slot:subtitle>

            {{-- MENU --}}
            <x-slot:menu>
                <x-button icon="tabler.woman" tooltip="Sepete Ekle" class="btn-sm" spinner />
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-primary p-3 shadow-lg text-sm"> %40 İndirim </span>
            </div>
            CİLDİNİZ YENİLENSİN
            <div>
                <x-list-item :item="$genders">
                    <x-slot:value>
                        PROFESYONEL CİLT BAKIMI
                    </x-slot:value>
                    <x-slot:sub-value>
                        CİLT BAKIMI
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-badge value="8" class="badge-primary" />
                    </x-slot:actions>
                </x-list-item>
            </div>
            <div class="mt-2">
                <x-icon name="tabler.check" label="BAKIRKÖY, MECİDİYEKÖY" />
            </div>

        </x-card>
        <x-card shadow class="card w-full bg-base-100 shadow-xl  cursor-pointer border border-blue-300"
            wire:click="handleClick">
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                8 SEANS EPİLASYON TÜM VÜCUT
            </x-slot:title>

            <x-slot:subtitle>
                @price(150000)
            </x-slot:subtitle>

            {{-- MENU --}}
            <x-slot:menu>
                <x-button icon="tabler.man" tooltip="Sepete Ekle" class="btn-sm" spinner />
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-primary p-3 shadow-lg text-sm"> %40 İndirim </span>
            </div>
            İSTENMEYEN TÜYLERİNİZDEN KURTULMAK İÇİN BU PAKET
            <div>
                <x-list-item :item="$genders">
                    <x-slot:value>
                        KOLTUK ALTI
                    </x-slot:value>
                    <x-slot:sub-value>
                        EPİLASYON
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-badge value="8" class="badge-primary" />
                    </x-slot:actions>
                </x-list-item>
                <x-list-item :item="$genders">
                    <x-slot:value>
                        KOLTUK ALTI
                    </x-slot:value>
                    <x-slot:sub-value>
                        EPİLASYON
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-badge value="8" class="badge-primary" />
                    </x-slot:actions>
                </x-list-item>
                <x-list-item :item="$genders">
                    <x-slot:value>
                        KOLTUK ALTI
                    </x-slot:value>
                    <x-slot:sub-value>
                        EPİLASYON
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-badge value="8" class="badge-primary" />
                    </x-slot:actions>
                </x-list-item>
            </div>
            <div class="mt-2">
                <x-icon name="tabler.check" label="BAKIRKÖY, MECİDİYEKÖY" />
            </div>

        </x-card>
        <x-card shadow class="card w-full bg-base-100 shadow-xl  cursor-pointer border border-gray-300"
            wire:click="handleClick">
            {{-- TITLE --}}
            <x-slot:title class="text-lg font-black">
                5 SEANS KALICI OJE
            </x-slot:title>

            <x-slot:subtitle>
                @price(150000)
            </x-slot:subtitle>

            {{-- MENU --}}
            <x-slot:menu>
                <x-button icon="tabler.friends" tooltip="Sepete Ekle" class="btn-sm" spinner />
            </x-slot:menu>
            <div class="absolute top-0 right-0 -mt-4 -mr-1">
                <span class="badge badge-primary p-3 shadow-lg text-sm"> %40 İndirim </span>
            </div>
            DÜZENLİ TIRNAK
            <div>
                <x-list-item :item="$genders">
                    <x-slot:value>
                        KALICI OJE
                    </x-slot:value>
                    <x-slot:sub-value>
                        TIRNAK BAKIM
                    </x-slot:sub-value>
                    <x-slot:actions>
                        <x-badge value="5" class="badge-primary" />
                    </x-slot:actions>
                </x-list-item>
            </div>
            <div class="mt-2">
                <x-icon name="tabler.check" label="BAKIRKÖY, MECİDİYEKÖY" />
            </div>
        </x-card>
    </div>
</div>
