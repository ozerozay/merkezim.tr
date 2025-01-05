<div>
    <!-- Özelleştir Dropdown -->
    <div class="flex justify-end mb-4">
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-primary btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm">Özelleştir</span>
            </label>

            <div tabindex="0" class="dropdown-content z-[1] w-64 p-3 shadow-2xl bg-base-100 rounded-box">
                <div class="flex flex-col gap-1">
                    <div class="text-sm font-medium px-2 pb-2 border-b border-base-200">
                        Widget'ları Özelleştir
                    </div>
                    
                    @foreach($userWidgets as $widget)
                        <label class="flex items-center gap-3 px-2 py-2 hover:bg-base-200 rounded-lg cursor-pointer transition-colors">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    @switch($widget['type'])
                                        @case('sale')
                                            <span class="text-primary text-base">💰</span>
                                            @break
                                        @case('appointment')
                                            <span class="text-primary text-base">📅</span>
                                            @break
                                        @case('last_transactions')
                                            <span class="text-primary text-base">🏦</span>
                                            @break
                                        @default
                                            <span class="text-primary text-base">📊</span>
                                    @endswitch
                                    <span class="text-sm">{{ $widget['label'] }}</span>
                                </div>
                            </div>
                            <input type="checkbox" 
                                   class="toggle toggle-primary toggle-sm"
                                   wire:click="toggleWidget({{ $widget['id'] }})"
                                   @checked($widget['visible']) />
                        </label>
                    @endforeach

                    <div class="text-xs text-base-content/60 mt-2 px-2 pt-2 border-t border-base-200">
                        Widget'ları göster/gizle ve yeniden sırala
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widget'lar -->
    <livewire:admin.dashboard-widgets wire:key="dashboard-widgets-{{ Str::random(10) }}" />
</div> 