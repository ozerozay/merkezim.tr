@props([
    'title' => '',
    'description' => '',
    'icon' => '',
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 text-center']) }}>
    <!-- İkon -->
    @if($icon)
        <div class="w-16 h-16 mb-4 rounded-full bg-base-200 flex items-center justify-center">
            <x-icon :name="$icon" class="w-8 h-8 text-base-content/70" />
        </div>
    @endif

    <!-- Başlık -->
    @if($title)
        <h3 class="text-lg font-medium mb-2">
            {{ $title }}
        </h3>
    @endif

    <!-- Açıklama -->
    @if($description)
        <p class="text-base-content/60 text-sm mb-6">
            {{ $description }}
        </p>
    @endif

    <!-- Aksiyonlar -->
    @if(isset($actions))
        <div class="flex flex-wrap items-center justify-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div> 