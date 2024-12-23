<div>
    <x-header title="{{ __('client.menu_shop') }}" subtitle="{{ __('client.page_shop_package_subtitle') }}" separator
        progress-indicator>
        <x-slot:actions>
            <div class="flex flex-wrap gap-2">
                @auth
                    <x-button label="Kendi Paketini Oluştur"
                        wire:click="$dispatch('slide-over.open', {component: 'web.shop.create-package'})" icon="o-plus-circle"
                        class="btn btn-primary" />
                @endauth
                @guest
                    <x-button label="Kendi Paketini Oluştur"
                        wire:click="$dispatch('slide-over.open', {component: 'login.login-page'})" icon="o-plus-circle"
                        class="btn btn-primary" />
                @endguest

                <x-button label="Filtrele" icon="o-funnel" class="btn btn-outline" />

                @if (1 == 2)
                    <div class="w-full lg:w-auto">
                        <x-input placeholder="{{ __('client.search') }}..." wire:model.live.debounce.500ms="search"
                            icon="o-magnifying-glass" class="border-neutral text-sm" />
                    </div>

                    <x-dropdown>
                        <x-slot:trigger>
                            <x-button label="Şube" icon-right="o-chevron-down" :badge="count($gender_id) ?: null"
                                class="btn-outline" />
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
                            <x-button label="Cinsiyet" icon-right="o-chevron-down" :badge="count($gender_id) ?: null"
                                class="btn-outline" />
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
                            <x-button label="Kategori" icon-right="o-chevron-down" :badge="count($categories_id) ?: null"
                                class="btn-outline" />
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
                @endif
            </div>
        </x-slot:actions>
    </x-header>
    <div class="grid md:grid-cols-2  lg:grid-cols-4 gap-4 mt-5">
        @foreach ($packages as $package)
            @php
                $icon = 'woman';
                $color = 'pink';
                if ($package->package->gender == 0) {
                    $icon = 'friends';
                    $color = 'grey';
                } elseif ($package->package->gender == 2) {
                    $icon = 'man';
                    $color = 'blue';
                }

            @endphp
            <x-card shadow class="card w-full bg-base-100 shadow-xl cursor-pointer ">

                <x-slot:title class="text-lg
                font-black">
                    {{ $package->name }}
                </x-slot:title>

                <x-slot:subtitle>
                    @price($package->price)
                </x-slot:subtitle>

                {{-- MENU --}}
                <x-slot:menu>
                    <x-button icon="tabler.{{ $icon }}" tooltip="Sepete Ekle" class="btn-sm" spinner />
                </x-slot:menu>
                @if ($package->discount_text)
                    <div class="absolute top-0 right-0 -mt-4 -mr-1">
                        <span class="badge badge-primary p-3 shadow-lg text-sm"> {{ $package->discount_text }} </span>
                    </div>
                @endif
                {{ $package->description }}
                <div>
                    @foreach ($package->package->items as $item)
                        <x-list-item :item="$item">
                            <x-slot:value>
                                {{ $item->service->name }}
                            </x-slot:value>
                            <x-slot:sub-value>
                                {{ $item->service->category->name }}
                            </x-slot:sub-value>
                            <x-slot:actions>
                                <x-badge value="{{ $item->quantity }}" class="badge-primary" />
                            </x-slot:actions>
                        </x-list-item>
                    @endforeach
                </div>
                <x-hr />
                <x-button class="btn btn-full w-full btn-outline" wire:click="addToCart({{ $package->id }})"
                    label="Sepete Ekle" />
            </x-card>
        @endforeach

    </div>
</div>