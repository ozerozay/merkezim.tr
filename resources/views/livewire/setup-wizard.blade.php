<div class="flex items-center justify-center bg-gray-50 dark:bg-[#1e1b29]">
    <div class="w-full max-w-2xl p-6 bg-white dark:bg-[#2d2740] rounded-lg shadow">
        <!-- Progress Bar -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-4 text-[#4b367c] dark:text-[#e0d7ff]">👏 Az kaldı, Cihat Özer</h1>
            <div class="w-full bg-[#eae6f7] dark:bg-[#3e3659] rounded-full h-2.5">
                <div class="bg-[#6b4aa1] dark:bg-[#a065ff] h-2.5 rounded-full" style="width: 50%;"></div>
            </div>
        </div>

        <div class="p-6">
            <button class="btn btn-primary">Mor Tema Butonu</button>
        </div>
        <div class="p-6">
            <!-- Tema Değiştirici -->
            <button
                class="btn btn-primary"
                @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
            >
                Tema Değiştir
            </button>

            <!-- Test İçeriği -->
            <div class="card bg-base-100 dark:bg-neutral text-base-content dark:text-neutral-content mt-4">
                <div class="card-body">
                    <h2 class="card-title">Class-based Dark Mode</h2>
                    <p>Bu içerik, class-based dark mode ile uyumlu çalışmalıdır.</p>
                </div>
            </div>
        </div>
        <!-- Steps -->
        <div class="space-y-4">
            @foreach ($steps as $index => $step)
                <div class="border rounded-lg shadow-sm border-gray-300 dark:border-[#4a4163]">
                    <!-- Step Header -->
                    <div class="flex justify-between items-center p-4 bg-[#f6f4fc] dark:bg-[#3e3659]">
                        <h2 class="font-semibold text-lg text-[#4b367c] dark:text-[#e0d7ff]">{{ $step['title'] }}</h2>
                        <button
                            wire:click="toggleStep({{ $index }})"
                            class="text-sm text-[#8566b1] dark:text-[#c2b4ff] hover:underline">
                            {{ $step['expanded'] ? 'Kapat' : 'Aç' }}
                        </button>
                    </div>

                    <!-- Substeps -->
                    @if ($step['expanded'])
                        <div class="p-4 space-y-2">
                            @if (!empty($step['substeps']))
                                @foreach ($step['substeps'] as $subIndex => $substep)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div
                                                class="w-6 h-6 flex items-center justify-center rounded-full {{ $substep['completed'] ? 'bg-[#6b4aa1] dark:bg-[#a065ff]' : 'bg-[#eae6f7] dark:bg-[#4a4163]' }}">
                                                @if ($substep['completed'])
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <span
                                                class="ml-3 text-[#4b367c] dark:text-[#e0d7ff]">{{ $substep['title'] }}</span>
                                        </div>
                                        @if (!$substep['completed'])
                                            <button
                                                wire:click="toggleSubstep({{ $index }}, {{ $subIndex }})"
                                                class="text-[#6b4aa1] hover:underline dark:text-[#a065ff]">
                                                Kullanıcı ekle
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-sm text-[#8566b1] dark:text-[#c2b4ff]">Bu adım için işlem gerekmez.</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
