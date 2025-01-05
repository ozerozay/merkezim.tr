<div wire:key="ckvmkc-{{Str::random(10)}}" class="container mx-auto px-2 sm:px-4">
    <div wire:sortable="updateOrder" wire:key="ckvxxmkc-{{Str::random(10)}}"
         class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-2 sm:gap-4">
        @foreach ($widgets as $widget)
            @if ($widget->visible)
                <div wire:key="widget-{{ $widget->id }}" 
                     class="bg-base-200 rounded-lg {{ \App\Enum\AdminHomeWidgetType::from($widget->type)->getClasses() }}">
                    @php
                        $component = \App\Enum\AdminHomeWidgetType::from($widget->type)->component();
                    @endphp
                    @livewire($component, [], key('xzca-' . Str::random(10)))
                </div>
            @endif
        @endforeach
    </div>
</div>
