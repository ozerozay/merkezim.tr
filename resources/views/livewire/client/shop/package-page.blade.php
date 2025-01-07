<div class="relative text-base-content p-2 min-h-[200px]">
    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <span class="loading loading-spinner loading-md text-primary"></span>
                <span class="text-sm text-base-content/70">Yükleniyor...</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="h-full flex flex-col">
        <!-- Header Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <i class="text-2xl text-primary">🛍️</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Online Mağaza</h2>
                        <p class="text-sm text-base-content/70">Size özel hazırlanmış paketlerimizi inceleyin</p>
                    </div>
                    <livewire:client.shop.cart-dropdown/>

                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Cinsiyet Toggle -->
                    <button 
                        wire:click="toggleGender"
                        class="btn btn-sm btn-ghost gap-2"
                        x-data
                    >
                        @if($currentGender === 2)
                            <x-icon name="tabler.man" class="w-4 h-4"/>
                            <span class="text-sm">Erkek</span>
                        @else
                            <x-icon name="tabler.woman" class="w-4 h-4"/>
                            <span class="text-sm">Kadın</span>
                        @endif
                    </button>

                    <!-- Arama -->
                    <div class="w-full md:w-64">
                        <x-input 
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Paket Ara..."
                            icon="tabler.search"
                        />
                    </div>

                    <!-- Sıralama -->
                    <x-dropdown>
                        <x-slot:trigger>
                            <x-button 
                                icon="tabler.sort-ascending" 
                                label="Sırala"
                                class="btn-outline btn-sm"
                            />
                        </x-slot:trigger>

                        <div class="w-48">
                            @foreach($sortOptions as $value => $label)
                                <x-menu-item 
                                    :title="$label"
                                    wire:click="$set('sort', '{{ $value }}')"
                                    :active="$sort === $value"
                                />
                            @endforeach
                        </div>
                    </x-dropdown>

                    <!-- Filtreleri Temizle -->
                    @if($hasFilters)
                        <button 
                            wire:click="resetFilters"
                            class="btn btn-ghost btn-sm"
                        >
                            <x-icon name="tabler.x" class="w-4 h-4"/>
                            Temizle
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Paket Listesi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($packages as $package)
                @include('livewire.client.shop.package-card', ['package' => $package])
            @empty
                <div class="col-span-full">
                    <x-empty-state 
                        title="Paket Bulunamadı"
                        description="Arama kriterlerinize uygun paket bulunamadı."
                        icon="tabler.package-off"
                    />
                </div>
            @endforelse
        </div>

        <!-- Sayfalama -->
        @if($packages->hasPages())
            <div class="mt-6">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>
