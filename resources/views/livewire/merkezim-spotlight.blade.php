<div class="relative" wire:key="merkezim-spotlight">
    <div class="relative z-50">
        @if($isOpen)
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity duration-200" 
                 wire:click="close"></div>

            <!-- Modal Container -->
            <div class="fixed inset-0 flex items-start sm:justify-center sm:pt-[15vh]">
                <div class="relative w-full h-full sm:h-auto sm:w-[640px]" 
                     x-on:click.outside="$wire.close()">
                    <div class="h-full sm:h-auto m-0 sm:m-4 bg-white dark:bg-gray-900 sm:rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/10 flex flex-col">
                        <!-- Search Input -->
                        <div class="sticky top-0 z-10 p-4 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <input
                                    wire:model.live.debounce.300ms="search"
                                    type="text"
                                    class="flex-1 h-full bg-transparent border-0 focus:ring-0 focus:outline-none text-base sm:text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                                    placeholder="{{ $this->getCurrentPathLabel() }}'de ara..."
                                    wire:keydown.escape.prevent="close"
                                    wire:keydown.up.prevent="decrementSelected"
                                    wire:keydown.down.prevent="incrementSelected"
                                    wire:keydown.enter.prevent="selectItem({{ $selectedIndex }})"
                                    autocomplete="off"
                                    spellcheck="false"
                                >
                                @if(count($currentPath) > 0)
                                    <button wire:click="goBack" class="p-2 sm:p-1 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400">
                                        <svg class="w-5 h-5 sm:w-4 sm:h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                @endif
                                <kbd class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 rounded">
                                    <span class="text-xs">⌘</span>
                                    <span>K</span>
                                </kbd>
                            </div>
                        </div>

                        <!-- Results -->
                        <div class="flex-1 overflow-y-auto overscroll-contain">
                            <!-- Loading State -->
                            <div wire:loading wire:target="search" class="p-4">
                                <div class="flex items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Aranıyor...</span>
                                </div>
                            </div>

                            <!-- Results List -->
                            <div wire:loading.remove wire:target="search" class="py-2">
                                @if(count($filteredItems))
                                    @php
                                        $groupedItems = collect($filteredItems)->groupBy('group');
                                        $globalIndex = 0;
                                    @endphp
                                    
                                    @foreach($groupedItems as $group => $items)
                                        @if($group)
                                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-800/50">
                                                {{ $group }}
                                            </div>
                                        @endif
                                        
                                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                                            @foreach($items as $item)
                                                <div wire:key="{{ $item['id'] }}"
                                                     class="group relative flex items-center gap-3 px-4 py-3 cursor-pointer transition-all duration-75 {{ $selectedIndex === $globalIndex ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-900 dark:text-primary-100' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}"
                                                     wire:click="selectItem({{ $globalIndex }})">
                                                    
                                                    <!-- İkon ve Label -->
                                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                                        <span class="flex-none text-lg opacity-70">{{ $item['icon'] }}</span>
                                                        <div class="flex-1 truncate">
                                                            <div class="font-medium">{{ $item['label'] }}</div>
                                                            @if(isset($item['description']))
                                                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                                    {{ $item['description'] }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Sağ Taraf İkonları -->
                                                    <div class="flex items-center gap-2">
                                                        @if(isset($item['children']))
                                                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        @endif
                                                        
                                                        @if($selectedIndex === $globalIndex)
                                                            <kbd class="hidden sm:inline-flex px-2 py-1 text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 rounded">
                                                                enter
                                                            </kbd>
                                                        @endif
                                                    </div>
                                                </div>
                                                @php
                                                    $globalIndex++;
                                                @endphp
                                            @endforeach
                                        </div>
                                    @endforeach
                                @else
                                    <div class="px-4 py-12 text-center">
                                        <div class="inline-flex flex-col items-center justify-center gap-3 text-gray-400 dark:text-gray-500">
                                            <div class="animate-bounce">
                                                <svg class="w-12 h-12 stroke-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            
                                            <div class="space-y-1">
                                                <p class="text-base font-medium">
                                                    Sonuç bulunamadı
                                                </p>
                                                <p class="text-sm">
                                                    @if(empty($search))
                                                        Arama yapmak için yazmaya başlayın
                                                    @else
                                                        "{{ $search }}" için sonuç bulunamadı
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="mt-4 text-sm">
                                                <p class="font-medium mb-2">Öneriler                                                </p>
                                                <ul class="list-disc list-inside text-left">
                                                    <li>• Yazım hatası olmadığından emin olun</li>
                                                    <li>• Farklı anahtar kelimeler deneyin</li>
                                                    <li>• Daha genel terimler kullanın</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="sticky bottom-0 z-10 p-4 bg-white dark:bg-gray-900 text-xs text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-0">
                                <!-- Sol Grup: Tema ve Hava Durumu -->
                                <div class="flex items-center justify-between sm:justify-start gap-4">
                                    <x-theme-toggle class="btn btn-ghost btn-sm">
                                        <span class="text-lg sm:text-base">🌙</span>
                                    </x-theme-toggle>

                                    <div class="flex items-center gap-2 px-3 py-2 sm:py-1.5 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                                        <span class="text-lg sm:text-base">{{ $weather['icon'] }}</span>
                                        <span class="font-medium">{{ $weather['temp'] }}</span>
                                        <span class="text-gray-400 dark:text-gray-500 sm:hidden">{{ $weather['description'] }}</span>
                                        <span class="hidden sm:inline text-gray-400 dark:text-gray-500">{{ $weather['description'] }}</span>
                                    </div>
                                </div>

                                <!-- Klavye Kısayolları -->
                                <div class="flex items-center justify-between sm:justify-end gap-2 sm:gap-4">
                                    <span class="flex items-center gap-1 sm:gap-2">
                                        <kbd class="px-2 py-1.5 sm:px-1.5 sm:py-1 text-xs rounded bg-gray-100 dark:bg-gray-800">↑</kbd>
                                        <kbd class="px-2 py-1.5 sm:px-1.5 sm:py-1 text-xs rounded bg-gray-100 dark:bg-gray-800">↓</kbd>
                                        <span>gezin</span>
                                    </span>
                                    <span class="flex items-center gap-1 sm:gap-2">
                                        <kbd class="px-2 py-1.5 sm:px-1.5 sm:py-1 text-xs rounded bg-gray-100 dark:bg-gray-800">↵</kbd>
                                        <span>seç</span>
                                    </span>
                                    <button 
                                        wire:click="close"
                                        class="flex items-center gap-1 sm:gap-2 px-3 py-1.5 sm:px-2 sm:py-1 text-xs rounded bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors"
                                    >
                                        <span>kapat</span>
                                        <kbd class="px-2 py-1.5 sm:px-1.5 sm:py-1 text-xs rounded bg-gray-200 dark:bg-gray-700">esc</kbd>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Simple Response Modal -->
    <div class="relative z-50">
        @if($isModalOpen)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm" 
                 wire:click="closeModal"></div>
            
            <div class="fixed inset-0 sm:inset-x-4 sm:top-8 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 sm:top-[15vh] sm:w-[640px] h-full sm:h-auto max-h-[85vh] overflow-y-auto bg-white dark:bg-gray-900 sm:rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/10"
                 x-on:click.outside="$wire.closeModal()">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $modalTitle }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <div class="prose dark:prose-invert max-w-none whitespace-pre-line">
                        {!! nl2br(e($modalContent)) !!}
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                    <button wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-md transition-colors">
                        Kapat
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>