<div>
    <!-- Header -->
    <x-header title="Anasayfa" subtitle="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" separator>
        <x-slot:actions>
            <!-- Tema Seçici -->
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost gap-2">
                    <span class="text-xl">🎨</span>
                    <span>Tema</span>
                </label>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-200 rounded-box w-52">
                    <li class="menu-title">Açık Temalar</li>
                    <li>
                        <button wire:click="setTheme('morTema')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#FF69B4]"></span>
                            Mor
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTheme('lavanderTema')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#9B7EDE]"></span>
                            Lavanta
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTheme('mintTema')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#98FF98]"></span>
                            Nane
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTheme('peachTema')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#FFDAB9]"></span>
                            Şeftali
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTheme('skyTema')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#87CEEB]"></span>
                            Gökyüzü
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTheme('roseTema')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#FFB6C1]"></span>
                            Gül
                        </button>
                    </li>
                    <div class="divider my-0">Koyu Temalar</div>
                    <li>
                        <button wire:click="setTheme('morTemaDark')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#FF69B4] ring-1 ring-white/20"></span>
                            Mor (Koyu)
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTheme('lavanderTemaDark')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#9B7EDE] ring-1 ring-white/20"></span>
                            Lavanta (Koyu)
                        </button>
                    </li>
                    <li>
                        <button wire:click="setTheme('mintTemaDark')" class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#98FF98] ring-1 ring-white/20"></span>
                            Nane (Koyu)
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Dark/Light Mode Toggle -->
            <button class="btn btn-ghost btn-circle" onclick="toggleDarkMode()">
                <span class="light-mode-icon text-xl">☀️</span>
                <span class="dark-mode-icon hidden text-xl">🌙</span>
            </button>

            <!-- Özelleştir Dropdown -->
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-primary btn-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm">Özelleştir</span>
                </label>

                <div tabindex="0" class="dropdown-content z-[1] w-72 p-3 shadow-2xl bg-base-100 rounded-box">
                    <div class="flex flex-col gap-1">
                        <!-- Widget'lar -->
                        <div class="text-sm font-medium px-2 pb-2 mt-4 border-b border-base-200">
                            Widget'ları Özelleştir
                        </div>
                        
                        <div wire:sortable="updateWidgetOrder" wire:sortable.options="{ animation: 100 }" class="space-y-2 py-2">
                            @foreach($userWidgets as $widget)
                                <div wire:sortable.item="{{ $widget['id'] }}" wire:key="widget-{{ $widget['id'] }}"
                                     class="flex items-center gap-3 p-2 hover:bg-base-200 rounded-lg transition-colors">
                                    <div wire:sortable.handle class="cursor-move">
                                        <div class="w-10 h-10 rounded-lg {{ $widget['bgColor'] }} flex items-center justify-center">
                                            <span class="text-xl">{{ $widget['icon'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-sm">{{ $widget['label'] }}</div>
                                        <div class="text-xs text-base-content/60">Sürükle & bırak ile sırala</div>
                                    </div>
                                    <input type="checkbox" 
                                           class="toggle toggle-primary toggle-sm"
                                           wire:click="toggleWidget({{ $widget['id'] }})"
                                           @checked($widget['visible']) />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </x-slot:actions>
    </x-header>

    <!-- Widget'lar -->
    <livewire:admin.dashboard-widgets wire:key="dashboard-widgets-{{ $uniqueId }}" />
</div>
