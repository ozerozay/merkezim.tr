<div class="container mx-auto px-2 sm:px-4">
    <div wire:sortable="updateOrder"
         wire:key="widgets-container-{{ $uniqueId }}"
         class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-2 sm:gap-4">
        @foreach ($widgets as $widget)
            @if ($widget->visible)
                @php
                    $component = \App\Enum\AdminHomeWidgetType::from($widget->type)->component();
                    $widgetKey = "widget-{$uniqueId}-{$widget->id}";
                @endphp

                <div wire:key="{{ $widgetKey }}" 
                     wire:sortable.item="{{ $widget->id }}"
                     class="bg-base-200 rounded-lg {{ \App\Enum\AdminHomeWidgetType::from($widget->type)->getClasses() }}">
                    <div x-data="{ isLoading: true }"
                         x-init="
                            $wire.loadWidget('{{ $widget->id }}').then(() => {
                                isLoading = false;
                            });
                         ">
                        <div x-show="isLoading" 
                             class="p-4 flex items-center justify-center">
                            <span class="loading loading-spinner loading-md text-primary"></span>
                        </div>
                        
                        <div x-show="!isLoading" x-cloak>
                            @if(isset($loadedWidgets[$widget->id]) && $loadedWidgets[$widget->id])
                                @livewire($component, ['widgetId' => $widget->id], key("widget-instance-{$uniqueId}-{$widget->id}"))
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
