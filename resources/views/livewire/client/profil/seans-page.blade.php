<div>
    <x-header title="{{ __('client.menu_seans') }}" separator progress-indicator>
        <x-slot:actions>
            @if ($add_seans)
                <x-button class="btn-primary btn-sm" icon="o-plus">
                    {{ __('client.page_seans_add_seans') }}
                </x-button>
            @endif
        </x-slot:actions>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($data as $service)
            <x-card title="{{ $service->service->name }}" separator class="mb-2 card w-full bg-base-100 cursor-pointer">
                <x-slot:menu>
                    <x-button icon="o-plus" class="btn-circle btn-outline btn-sm" />
                </x-slot:menu>
                @php
                    $remaining_percentage = ($service->remaining / $service->total) * 100;

                    $color = 'error';
                    if ($remaining_percentage >= 50) {
                        $color = 'success';
                    } elseif ($remaining_percentage == 50) {
                        $color = 'warning';
                    } elseif ($remaining_percentage < 50) {
                        $color = 'error';
                    }

                @endphp
                <x-progress value="{{ number_format($remaining_percentage) }}" max="100"
                    class="progress-{{ $color }} h-3" />
                <x-list-item :item="$service">
                    <x-slot:value>
                        Kategori
                    </x-slot:value>
                    <x-slot:actions>
                        {{ $service->service->category->name ?? '' }}
                    </x-slot:actions>
                </x-list-item>
                <x-list-item :item="$seans">
                    <x-slot:value>
                        Kalan
                    </x-slot:value>
                    <x-slot:actions>
                        {{ $service->remaining }}
                    </x-slot:actions>
                </x-list-item>
                <x-list-item :item="$seans">
                    <x-slot:value>
                        Toplam
                    </x-slot:value>
                    <x-slot:actions>
                        {{ $service->total }}
                    </x-slot:actions>
                </x-list-item>
            </x-card>
        @endforeach
    </div>
</div>
