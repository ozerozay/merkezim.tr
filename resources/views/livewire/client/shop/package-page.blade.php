<div>
    <x-header title="{{ __('client.menu_shop') }}" subtitle="{{ __('client.page_shop_package_subtitle') }}" separator
              progress-indicator>
        <x-slot:actions>
            <div class="flex flex-wrap gap-2">
                @auth
                    <x-button label="Kendi Paketini Oluştur"
                              wire:click="$dispatch('slide-over.open', {component: 'web.shop.create-package'})"
                              icon="o-plus-circle"
                              class="btn btn-primary"/>
                @endauth
                @guest
                    <x-button label="Kendi Paketini Oluştur"
                              wire:click="$dispatch('slide-over.open', {component: 'login.login-page'})"
                              icon="o-plus-circle"
                              class="btn btn-primary"/>
                @endguest

                <x-button label="Filtrele" icon="o-funnel" class="btn btn-outline"/>

                @if (1 == 2)
                    <div class="w-full lg:w-auto">
                        <x-input placeholder="{{ __('client.search') }}..." wire:model.live.debounce.500ms="search"
                                 icon="o-magnifying-glass" class="border-neutral text-sm"/>
                    </div>

                    <x-dropdown>
                        <x-slot:trigger>
                            <x-button label="Şube" icon-right="o-chevron-down" :badge="count($gender_id) ?: null"
                                      class="btn-outline"/>
                        </x-slot:trigger>

                        <x-menu-item title="Sıfırla" icon="o-x-mark" @click="$wire.set('gender_id', [])"/>

                        <x-menu-separator/>

                        @foreach ($genders as $gender)
                            <x-menu-item @click.stop="">
                                <x-checkbox label="{{ $gender['name'] }}" value="{{ $gender['id'] }}"
                                            wire:model.live="gender_id"/>
                            </x-menu-item>
                        @endforeach
                    </x-dropdown>

                    <x-dropdown>
                        <x-slot:trigger>
                            <x-button label="Cinsiyet" icon-right="o-chevron-down" :badge="count($gender_id) ?: null"
                                      class="btn-outline"/>
                        </x-slot:trigger>

                        <x-menu-item title="Sıfırla" icon="o-x-mark" @click="$wire.set('gender_id', [])"/>

                        <x-menu-separator/>

                        @foreach ($genders as $gender)
                            <x-menu-item @click.stop="">
                                <x-checkbox label="{{ $gender['name'] }}" value="{{ $gender['id'] }}"
                                            wire:model.live="gender_id"/>
                            </x-menu-item>
                        @endforeach
                    </x-dropdown>

                    <x-dropdown label="Kategori" class="btn-outline">
                        <x-slot:trigger>
                            <x-button label="Kategori" icon-right="o-chevron-down"
                                      :badge="count($categories_id) ?: null"
                                      class="btn-outline"/>
                        </x-slot:trigger>

                        <x-menu-item title="Sıfırla" icon="o-x-mark" @click="$wire.set('categories_id', [])"/>

                        <x-menu-separator/>

                        @foreach ($categories as $category)
                            <x-menu-item @click.stop="">
                                <x-checkbox :label="$category->name" :value="$category->id"
                                            wire:model.live="categories_id"/>
                            </x-menu-item>
                        @endforeach
                    </x-dropdown>

                    {{-- Clear filters --}}
                    @if ($hasFilters)
                        <x-button label="Sıfırla" icon="o-x-mark" wire:click="clearFilters"/>
                    @endif
                @endif
            </div>
        </x-slot:actions>
    </x-header>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        @foreach ($packages as $package)
            <livewire:web.shop.package-card :package="$package" :key="'axa'.$package->id"/>
        @endforeach
    </div>
</div>
