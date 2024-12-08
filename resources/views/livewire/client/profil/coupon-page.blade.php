<div>
    <x-header title="{{ __('client.menu_coupon') }}" separator progress-indicator />
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($data as $coupon)
            <x-card shadow class="card w-full bg-base-100 cursor-pointer border" wire:click="handleClick" separator>
                <x-slot:title class="text-lg font-black">
                    {{ $coupon->code }}
                </x-slot:title>
                <x-slot:menu>
                    @if ($coupon->discount_type)
                        %{{ $coupon->discount_amount }} İNDİRİM
                    @else
                        @price($coupon->discount_amount) İNDİRİM
                    @endif
                </x-slot:menu>
                <div class="absolute top-0 right-0 -mt-4 -mr-1">
                    <span class="badge badge-error p-3 shadow-lg text-sm"> Son {{ $coupon->remainingDay() }} Gün </span>
                </div>
                @if ($coupon->min_order > 0)
                    @price($coupon->min_order) TL ve ÜZERİ
                @endif
            </x-card>
        @endforeach
    </div>
</div>
