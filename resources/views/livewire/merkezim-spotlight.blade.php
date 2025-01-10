<div class="relative" 
     x-data="{ 
        isOpen: false,
        lockScroll() {
            document.body.style.overflow = 'hidden';
            document.body.style.touchAction = 'none';
        },
        unlockScroll() {
            document.body.style.overflow = '';
            document.body.style.touchAction = '';
        },
        toggle() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.lockScroll();
                $wire.toggle();
            } else {
                this.unlockScroll();
                $wire.close();
            }
        },
        close() {
            this.isOpen = false;
            this.unlockScroll();
            $wire.close();
        }
     }"
     x-init="
        window.addEventListener('keydown', e => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                toggle();
            }
            if (e.key === 'Escape' && isOpen) {
                close();
            }
        });
        $watch('isOpen', value => value ? lockScroll() : unlockScroll());

        Livewire.on('close-spotlight', () => {
            close();
        });
     "
     @merkezim-spotlight-toggle.window="toggle()"
>
    <div class="relative z-[100]" x-show="isOpen" x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm transition-opacity duration-200" 
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="close"
             x-on:click.outside="close"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 flex items-start justify-center sm:pt-[10vh]">
            <!-- Main Container -->
            <div class="relative w-full h-full sm:h-[calc(80vh-2rem)] sm:w-[640px] overflow-hidden" @click.stop>
                <div class="h-full m-0 sm:m-4 bg-white dark:bg-gray-900 sm:rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/10 flex flex-col">
                    <!-- Header -->
                    <div class="flex flex-col border-b border-gray-100 dark:border-gray-800">
                        <!-- Üst Kısım: Breadcrumb -->
                        <div class="flex items-center justify-between px-4 py-3">
                            <div class="flex items-center gap-2 text-sm">
                                <button wire:click="resetPath" 
                                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center gap-2">
                                    <span class="text-lg">🔍</span>
                                    <span class="font-medium">Ana Menü</span>
                                </button>

                                @foreach($this->getBreadcrumbs() as $index => $crumb)
                                    <span class="text-gray-400 dark:text-gray-500">/</span>
                                    @if($index === count($this->getBreadcrumbs()) - 1)
                                        <span class="text-gray-900 dark:text-gray-100">{{ $crumb['label'] }}</span>
                                    @else
                                        <button wire:click="goToPath('{{ $crumb['id'] }}')" 
                                                class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                            {{ $crumb['label'] }}
                                        </button>
                                    @endif
                                @endforeach

                                @if(!empty($search))
                                    <span class="text-gray-400 dark:text-gray-500">/</span>
                                    <span class="text-gray-900 dark:text-gray-100">Arama: "{{ $search }}"</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Hızlı erişim için
                                </span>
                                <kbd class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 rounded">
                                    <span class="text-xs">⌘</span>
                                    <span>K</span>
                                </kbd>
                            </div>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="flex-none sticky top-0 z-10 bg-white dark:bg-gray-900">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-800">
                            <div class="relative group">
                                <!-- Search Icon / Voice Button -->
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4"
                                     x-data="{ 
                                        isListening: false,
                                        recognition: null,
                                        isSupportedBrowser: 'SpeechRecognition' in window || 'webkitSpeechRecognition' in window,
                                        
                                        async startListening() {
                                            if (!this.isSupportedBrowser) {
                                                $wire.dispatch('notify', { 
                                                    type: 'error',
                                                    message: 'Tarayıcınız ses tanıma özelliğini desteklemiyor.'
                                                });
                                                return;
                                            }

                                            try {
                                                // Mikrofon izni iste
                                                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                                                stream.getTracks().forEach(track => track.stop()); // İzin sonrası stream'i kapat

                                                if (!this.recognition) {
                                                    this.recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
                                                    this.recognition.lang = 'tr-TR';
                                                    this.recognition.continuous = false;
                                                    this.recognition.interimResults = false;
                                                    
                                                    this.recognition.onresult = (event) => {
                                                        const text = event.results[0][0].transcript;
                                                        $wire.set('search', text);
                                                        this.stopListening();
                                                    };
                                                    
                                                    this.recognition.onerror = (event) => {
                                                        console.error('Speech recognition error:', event.error);
                                                        this.handleError(event.error);
                                                        this.stopListening();
                                                    };
                                                    
                                                    this.recognition.onend = () => {
                                                        this.stopListening();
                                                    };
                                                }
                                                
                                                this.recognition.start();
                                                this.isListening = true;
                                                
                                                $wire.dispatch('notify', { 
                                                    type: 'info',
                                                    message: 'Sizi dinliyorum...'
                                                });
                                                
                                            } catch (error) {
                                                console.error('Microphone error:', error);
                                                this.handleError(error.name || 'unknown');
                                            }
                                        },

                                        stopListening() {
                                            if (this.recognition) {
                                                this.recognition.stop();
                                            }
                                            this.isListening = false;
                                        },

                                        handleError(error) {
                                            let message = 'Bir hata oluştu.';
                                            
                                            switch(error) {
                                                case 'not-allowed':
                                                    message = 'Mikrofon izni verilmedi. Lütfen tarayıcı izinlerini kontrol edin.';
                                                    break;
                                                case 'NotAllowedError':
                                                    message = 'Mikrofon izni verilmedi. Lütfen tarayıcı izinlerini kontrol edin.';
                                                    break;
                                                case 'no-speech':
                                                    message = 'Ses algılanamadı. Lütfen tekrar deneyin.';
                                                    break;
                                                case 'network':
                                                    message = 'Ağ bağlantısı hatası. Lütfen internet bağlantınızı kontrol edin.';
                                                    break;
                                                case 'NotFoundError':
                                                    message = 'Mikrofon bulunamadı. Lütfen mikrofon bağlantınızı kontrol edin.';
                                                    break;
                                            }
                                            
                                            $wire.dispatch('notify', { 
                                                type: 'error',
                                                message: message
                                            });
                                        }
                                     }">
                                    <button @click="startListening()" 
                                            class="group flex items-center justify-center w-5 h-5 transition-colors"
                                            :class="{ 'text-primary-500 dark:text-primary-400': isListening, 'text-gray-400 dark:text-gray-500 hover:text-primary-500 dark:hover:text-primary-400': !isListening }">
                                        <!-- Mikrofon İkonu -->
                                        <svg x-show="!isListening" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                        
                                        <!-- Dinleme Animasyonu -->
                                        <svg x-show="isListening" class="w-5 h-5 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                                            <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Search Input -->
                                <input
                                    wire:model.live.debounce.300ms="search"
                                    type="text"
                                    placeholder="Menüde ara... (örn: ödeme, fatura, rapor)"
                                    class="w-full py-2.5 pl-11 pr-10 text-base bg-gray-50 dark:bg-gray-800/50 border-0 rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100 transition-all"
                                    @keydown.prevent.stop.enter="$wire.selectItem()"
                                    @keydown.prevent.arrow-up="$wire.decrementSelectedIndex()"
                                    @keydown.prevent.arrow-down="$wire.incrementSelectedIndex()"
                                    autofocus
                                />

                                <!-- Clear Button / Loading Container -->
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                    <!-- Loading Spinner -->
                                    <div wire:loading.delay wire:target="search">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>

                                    <!-- Clear Button -->
                                    <div wire:loading.delay.remove wire:target="search">
                                        @if($search)
                                            <button wire:click="resetSearch" 
                                                    class="p-1 rounded-lg text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-400 dark:text-gray-500">
                                                ⌘K
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Search Info -->
                            <div class="mt-2 px-1 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500">
                                <span>
                                    @if($search)
                                        "{{ $search }}" için arama sonuçları
                                    @else
                                        Tüm menü öğeleri
                                    @endif
                                </span>
                                <span class="hidden sm:block">
                                    {{ count($filteredItems) }} sonuç
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Component -->
                    <div wire:key="notification-container">
                        @if(count($notifications) > 0)
                            @include('livewire.merkezim-spotlight.components.notification')
                        @endif
                    </div>

                    <!-- Results -->
                    <div class="flex-1 min-h-0">
                        <div class="h-full overflow-y-auto overscroll-contain touch-pan-y">
                            @if($showPaymentForm)
                                @livewire('merkezim-spotlight.forms.payment-form', key('payment-form-' . Str::random(10)))
                            @else
                                <!-- Loading State -->
                                <div wire:loading.delay wire:target="search" class="flex items-center justify-center h-32">
                                    <div class="flex items-center gap-3 text-gray-400 dark:text-gray-500">
                                        <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Aranıyor...</span>
                                    </div>
                                </div>

                                <!-- Results List -->
                                <div wire:loading.delay.remove wire:target="search" class="pb-4">
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
                                                        
                                                        <div class="flex items-center gap-3 flex-1 min-w-0">
                                                            <span class="flex-none text-lg opacity-70">{{ $item['icon'] }}</span>
                                                            <div class="flex-1 truncate">
                                                                <div class="font-medium">{{ $item['label'] }}</div>
                                                                @if(isset($item['description']))
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                        {{ $item['description'] }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $globalIndex++;
                                                    @endphp
                                                @endforeach
                                            </div>
                                        @endforeach

                                        <!-- Kasa Transactions Table -->
                                        @if(end($this->currentPath) === 'kasa')
                                            <div class="mt-4 px-4">
                                                <livewire:kasa-hareketleri-table wire:key="kasa-hareketleri-table-{{ Str::random(10) }}" />
                                            </div>
                                        @endif
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
    </div>

    <!-- Simple Response Modal -->
    <div class="relative z-[100]">
        @if($isModalOpen)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm" 
                 wire:click="closeModal"></div>
            
            <div class="fixed inset-0 sm:inset-x-4 sm:top-8 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 sm:top-[15vh] sm:w-[640px] h-full sm:h-auto max-h-[85vh] overflow-y-auto overscroll-contain touch-pan-y bg-white dark:bg-gray-900 sm:rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/10"
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