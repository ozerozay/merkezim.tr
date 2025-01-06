@props([
    'title' => '',
    'value' => '',
    'description' => '',
    'icon' => '',
    'trend' => null,
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'rounded-lg p-6 ' . $class]) }}>
    <div class="flex items-center justify-between">
        <!-- Sol Taraf: İkon -->
        <div class="h-12 w-12 rounded-lg bg-base-content/5 flex items-center justify-center">
            <x-icon :name="$icon" class="w-6 h-6 text-base-content/70" />
        </div>

        <!-- Sağ Taraf: Trend (Varsa) -->
        @if($trend !== null)
            @php
                $trendValue = floatval($trend);
            @endphp
            <div @class([
                'flex items-center gap-1 text-sm font-medium',
                'text-success' => $trendValue >= 0,
                'text-error' => $trendValue < 0,
            ])>
                <x-icon 
                    :name="$trendValue >= 0 ? 'tabler.trending-up' : 'tabler.trending-down'" 
                    class="w-4 h-4" />
                <span>{{ abs($trendValue) }}%</span>
            </div>
        @endif
    </div>

    <!-- Değer ve Başlık -->
    <div class="mt-4 space-y-1">
        <h3 class="text-2xl font-semibold tracking-tight">
            {{ $value }}
        </h3>
        <p class="text-base-content/60 text-sm">
            {{ $title }}
        </p>
    </div>

    <!-- Açıklama (Varsa) -->
    @if($description)
        <p class="mt-4 text-xs text-base-content/60">
            {{ $description }}
        </p>
    @endif
</div> 