<div class="bg-base-100 rounded-xl border border-base-200 overflow-hidden hover:shadow-lg transition-all duration-300">
    <!-- Üst: Kategori ve Fiyat -->
    <div class="relative">
        <div class="aspect-[4/3] bg-gradient-to-br from-primary/5 to-primary/10 p-6 flex items-center justify-center">
            <div class="text-center">
                
                <div class="text-sm text-base-content/60"><h3 class="font-semibold text-base mb-3">{{ $package->name }}</h3></div>
            </div>
        </div>

        <!-- Rozetler -->
        <div class="absolute top-3 left-3">
            @if($package->package->gender == 2)
                <div class="bg-blue-500 text-white px-3 py-1.5 rounded-full flex items-center gap-2">
                    <x-icon name="tabler.man" class="w-4 h-4"/>
                    <span class="text-sm font-medium">Erkek</span>
                </div>
            @else
                <div class="bg-pink-500 text-white px-3 py-1.5 rounded-full flex items-center gap-2">
                    <x-icon name="tabler.woman" class="w-4 h-4"/>
                    <span class="text-sm font-medium">Kadın</span>
                </div>
            @endif
        </div>

        @if($package->discount_text)
            <div class="absolute top-3 right-3">
                <div class="bg-error text-white px-3 py-1.5 rounded-full flex items-center gap-2">
                    <x-icon name="tabler.discount" class="w-4 h-4"/>
                    <span class="text-sm font-medium">{{ $package->discount_text }}</span>
                </div>
            </div>
        @endif

        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2">
            <div class="bg-base-100 text-primary font-bold text-lg px-4 py-1.5 rounded-full shadow-sm border border-base-200">
                @price($package->price)
            </div>
        </div>
    </div>

    <!-- Alt: Detaylar -->
    <div class="p-6 pt-8">
        <h3 class="font-semibold text-base mb-3">{{ $package->name }}</h3>
        
        <div class="space-y-2 mb-4">
            @foreach($package->package->items->take(3) as $item)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-base-content/80">{{ $item->service->name }}</span>
                    <span class="badge badge-sm">{{ $item->quantity }}x</span>
                </div>
            @endforeach
            @if($package->package->items->count() > 3)
                <div class="text-center text-sm text-base-content/60">
                    +{{ $package->package->items->count() - 3 }} hizmet daha
                </div>
            @endif
        </div>

        <button class="btn btn-primary w-full" wire:click="addToCart({{ $package->id }})">
            <x-icon name="tabler.shopping-cart" class="w-4 h-4"/>
            Sepete Ekle
        </button>
    </div>
</div>