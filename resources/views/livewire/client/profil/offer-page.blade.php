<div>
    <x-header title="{{ __('client.menu_offer') }}" subtitle="{{ __('client.page_offer_subtitle') }}." separator
        progress-indicator>
        @if ($show_request)
            <x-slot:actions>
                <x-button class="btn-primary" icon="o-plus">
                    Teklif İste
                </x-button>
            </x-slot:actions>
        @endif
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($data as $offer)
            <x-card shadow class="card w-full bg-base-100 cursor-pointer border" wire:click="handleClick" separator>
                {{-- TITLE --}}
                <x-slot:title class="text-lg font-black">
                    Size Özel
                </x-slot:title>

                <x-slot:subtitle class="text-lg font-black">
                    {{ $offer->unique_id }}
                </x-slot:subtitle>

                {{-- MENU --}}
                <x-slot:menu>
                    @price($offer->price)
                </x-slot:menu>
                <div class="absolute top-0 right-0 -mt-4 -mr-1">
                    <span class="badge badge-error p-3 shadow-lg text-sm"> Son {{ $offer->remainingDay() }} Gün </span>
                </div>
                @foreach ($offer->items as $item)
                    <x-list-item :item="$item">
                        <x-slot:value>
                            {{ $item->itemable->name }}
                        </x-slot:value>
                        <x-slot:sub-value>
                            {{ $item->itemable->category->name ?? 'PAKET' }}
                        </x-slot:sub-value>
                        <x-slot:avatar>
                            <x-badge value="{{ $item->quantity }}" class="badge-primary" />
                        </x-slot:avatar>
                    </x-list-item>
                @endforeach
            </x-card>
        @endforeach
    </div>
</div>
