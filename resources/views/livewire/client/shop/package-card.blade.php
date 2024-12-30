<div
    class="card w-full bg-base-100 shadow-lg relative border border-gray-200 hover:shadow-2xl transition-shadow duration-300">
    @if ($package->discount_text)
        <div class="badge badge-primary absolute top-3 right-3 shadow-md">
            {{ $package->discount_text }}
        </div>
    @endif

    <div class="card-body">
        <div class="flex items-center justify-between">
            <h2 class="card-title text-xl font-bold text-gray-900 dark:text-gray-100">{{ $package->name }}</h2>
            <div
                class="text-2xl text-{{ $package->package->gender == 0 ? 'grey' : ($package->package->gender == 2 ? 'blue' : 'pink') }}">
                <x-icon
                    name="tabler.{{ $package->package->gender == 0 ? 'friends' : ($package->package->gender == 2 ? 'man' : 'woman') }}"
                    class="w-7 h-7"/>
            </div>
        </div>

        <p class="text-lg font-semibold text-primary dark:text-primary-light">@price($package->price)</p>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $package->description }}</p>

        <ul class="space-y-2">
            @foreach ($package->package->items as $item)
                <li class="flex items-center justify-between">
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->service->name }}</span>
                        <small class="text-gray-400 dark:text-gray-500">- {{ $item->service->category->name }}</small>
                    </div>
                    <div>
                        <span class="badge badge-primary p-2 text-sm">{{ $item->quantity }}</span>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            <div class="flex items-center justify-center space-x-4">
                <button class="btn btn-sm btn-outline btn-circle" wire:click="decrementItem">
                    <x-icon name="tabler.minus" class="w-5 h-5"/>
                </button>
                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $cartQuantity }}</span>
                <button class="btn btn-sm btn-outline btn-circle" wire:click="incrementItem">
                    <x-icon name="tabler.plus" class="w-5 h-5"/>
                </button>
            </div>
        </div>
    </div>
</div>
