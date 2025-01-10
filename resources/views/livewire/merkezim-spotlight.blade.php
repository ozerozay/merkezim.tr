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
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                            <!-- Sol Kısım: Breadcrumb -->
                            <nav class="flex items-center gap-2 text-sm">
                                <!-- Ana Menü -->
                                <button wire:click="resetPath" 
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-800/50 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <span class="text-base">🏠</span>
                                    <span class="font-medium">Ana Menü</span>
                                </button>

                                <!-- Breadcrumbs -->
                                @foreach($this->getBreadcrumbs() as $index => $crumb)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        
                                        @if($index === count($this->getBreadcrumbs()) - 1)
                                            <span class="px-3 py-1.5 rounded-lg bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400 font-medium">
                                                {{ $crumb['label'] }}
                                            </span>
                                        @else
                                            <button wire:click="goToPath('{{ $crumb['id'] }}')" 
                                                    class="px-3 py-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/50 text-gray-600 dark:text-gray-300 transition-colors">
                                                {{ $crumb['label'] }}
                                            </button>
                                        @endif
                                    </div>
                                @endforeach

                                <!-- Arama Terimi -->
                                @if(!empty($search))
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span class="px-3 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100">
                                            "{{ $search }}"
                                        </span>
                                    </div>
                                @endif
                            </nav>

                            <!-- Sağ Kısım: Kısayol -->
                            <div class="flex items-center gap-3"
                                 x-data="{ 
                                    time: new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' }),
                                    date: new Date().toLocaleDateString('tr-TR', { day: '2-digit', month: 'long', year: 'numeric' }),
                                    updateTime() {
                                        this.time = new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
                                    }
                                 }"
                                 x-init="setInterval(() => updateTime(), 1000)">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300" x-text="time"></span>
                                </div>
                                <div class="hidden sm:flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-500 dark:text-gray-400" x-text="date"></span>
                                </div>
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
                                        hasError: false,
                                        recognition: null,
                                        isSupportedBrowser: 'SpeechRecognition' in window || 'webkitSpeechRecognition' in window,
                                        
                                        async startListening() {
                                            if (!this.isSupportedBrowser) {
                                                return;
                                            }

                                            this.isListening = true;
                                            this.hasError = false;
                                            
                                            try {
                                                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                                                stream.getTracks().forEach(track => track.stop());

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
                                                        this.hasError = true;
                                                        this.handleError(event.error);
                                                        this.stopListening();
                                                    };
                                                    
                                                    this.recognition.onend = () => {
                                                        this.stopListening();
                                                    };
                                                }
                                                
                                                this.recognition.start();
                                            } catch (error) {
                                                this.hasError = true;
                                                this.handleError(error);
                                            }
                                        },
                                        
                                        stopListening() {
                                            this.isListening = false;
                                            if (this.recognition) {
                                                this.recognition.stop();
                                            }
                                        },
                                        
                                        handleError(error) {
                                            this.isListening = false;
                                            this.hasError = true;
                                            console.error('Speech recognition error:', error);
                                        }
                                     }">
                                    <button @click="startListening()" 
                                            class="flex items-center justify-center w-8 h-8 rounded-lg transition-all duration-200"
                                            :class="{
                                                'bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-500 animate-pulse': isListening,
                                                'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-primary-500 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-primary-400': !isListening && !hasError && isSupportedBrowser,
                                                'bg-gray-100 text-red-500 hover:bg-gray-200 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700': (!isListening && !isSupportedBrowser) || hasError
                                            }">
                                        <!-- Normal Mikrofon İkonu -->
                                        <svg x-show="!isListening && !hasError && isSupportedBrowser" 
                                             class="w-4 h-4" 
                                             xmlns="http://www.w3.org/2000/svg" 
                                             fill="none" 
                                             viewBox="0 0 24 24" 
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                        
                                        <!-- Dinleme Animasyonu -->
                                        <svg x-show="isListening" 
                                             class="w-4 h-4" 
                                             xmlns="http://www.w3.org/2000/svg" 
                                             fill="currentColor" 
                                             viewBox="0 0 24 24">
                                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                                            <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                                        </svg>

                                        <!-- Hata/Desteklenmiyor İkonu -->
                                        <svg x-show="(!isListening && !isSupportedBrowser) || hasError" 
                                             class="w-4 h-4" 
                                             xmlns="http://www.w3.org/2000/svg" 
                                             fill="none" 
                                             viewBox="0 0 24 24" 
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Search Input -->
                                <input
                                    wire:model.live.debounce.300ms="search"
                                    type="text"
                                    placeholder="Menüde ara... (örn: ödeme, fatura, rapor)"
                                    class="w-full py-2.5 pl-14 pr-10 text-base bg-gray-50 dark:bg-gray-800/50 border-0 rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-primary-500/20 dark:focus:ring-primary-400/20 placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100 transition-all"
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
                                            <button wire:click="resetSearch" 
                                                    class="p-1 rounded-lg text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
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
                        <!-- Mobil Görünüm -->
                        <div class="flex sm:hidden items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Tema Toggle (Mobil) -->
                                <x-theme-toggle class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/50 dark:hover:bg-gray-800 transition-colors">
                                    <span class="text-base">🌙</span>
                                </x-theme-toggle>

                                <!-- SMS Kontör (Mobil) -->
                                <div class="flex items-center gap-2 px-2.5 py-1.5 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">1000</span>
                                </div>

                                <!-- Lisans (Mobil) -->
                                <div class="flex items-center gap-2 px-2.5 py-1.5 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">365</span>
                                </div>
                            </div>

                            <!-- Kapat Butonu (Mobil) -->
                            <button 
                                @click="close()"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                            >
                                <span>Kapat</span>
                                <kbd class="px-1.5 py-0.5 text-xs rounded bg-gray-200 dark:bg-gray-700">ESC</kbd>
                            </button>
                        </div>

                        <!-- Masaüstü Görünüm -->
                        <div class="hidden sm:flex sm:items-center sm:justify-between">
                            <div class="flex items-center gap-4">
                                <x-theme-toggle class="btn btn-ghost btn-sm">
                                    <span class="text-base">🌙</span>
                                </x-theme-toggle>

                                <!-- SMS Kontör (Masaüstü) -->
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">1000</span>
                                    <span class="text-gray-400 dark:text-gray-500">SMS Kontörü</span>
                                </div>

                                <!-- Lisans (Masaüstü) -->
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-gray-800/50 rounded-md">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">365</span>
                                    <span class="text-gray-400 dark:text-gray-500">gün</span>
                                </div>

                                <!-- Destek (Masaüstü) -->
                                <a href="https://wa.me/905555555555" 
                                   target="_blank"
                                   class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/50 dark:hover:bg-gray-800 rounded-md transition-colors">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-300 font-medium">Destek</span>
                                </a>
                            </div>

                            <!-- Kapat Butonu (Masaüstü) -->
                            <button 
                                @click="close()"
                                class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                            >
                                <span>Kapat</span>
                                <kbd class="px-2 py-1 text-xs rounded bg-gray-200 dark:bg-gray-700">ESC</kbd>
                            </button>
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