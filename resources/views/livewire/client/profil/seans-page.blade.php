<div>
    <div class="relative text-base-content p-2 min-h-[200px]">
        <!-- Loading Indicator -->
        <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <div class="flex flex-col items-center gap-2">
                    <span class="loading loading-spinner loading-md text-primary"></span>
                    <span class="text-sm text-base-content/70">{{ __('client.page_seans.loading') }}</span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="h-full flex flex-col">
            <!-- Header Section with Stats -->
            <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary/10 rounded-xl">
                            <i class="text-2xl text-primary">📅</i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold">{{ __('client.page_seans.title') }}</h2>
                            <p class="text-sm text-base-content/70">{{ __('client.page_seans.subtitle') }}</p>
                        </div>
                    </div>

                    @if ($add_seans)
                        <x-button class="btn-primary" link="{{ route('client.shop.packages') }}" icon="o-plus">
                            {{ __('client.page_seans_add_seans') }}
                        </x-button>
                    @endif
                </div>

                @if($show_stats)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="stat bg-base-200/50 rounded-xl p-4">
                            <div class="stat-figure text-primary">
                                <i class="text-2xl">📦</i>
                            </div>
                            <div class="stat-title text-xs opacity-70">{{ __('client.page_seans.stats.total_services') }}</div>
                            <div class="stat-value text-lg">{{ $data->count() }}</div>
                        </div>
                        
                        <div class="stat bg-base-200/50 rounded-xl p-4">
                            <div class="stat-figure text-warning">
                                <i class="text-2xl">⏳</i>
                            </div>
                            <div class="stat-title text-xs opacity-70">{{ __('client.page_seans.stats.ongoing_services') }}</div>
                            <div class="stat-value text-lg">{{ $data->where('remaining', '>', 0)->count() }}</div>
                        </div>

                        <div class="stat bg-base-200/50 rounded-xl p-4">
                            <div class="stat-figure text-success">
                                <i class="text-2xl">✅</i>
                            </div>
                            <div class="stat-title text-xs opacity-70">{{ __('client.page_seans.stats.completed_services') }}</div>
                            <div class="stat-value text-lg">{{ $data->where('remaining', 0)->count() }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Ana İçerik -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sol Taraf - Devam Eden Hizmetler -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold">{{ __('client.page_seans.sections.ongoing.title') }}</h2>
                        <span class="text-sm text-base-content/60">
                            {{ __('client.page_seans.sections.ongoing.count', ['count' => $data->where('remaining', '>', 0)->count()]) }}
                        </span>
                    </div>

                    @foreach ($data->where('remaining', '>', 0) as $service)
                        @php
                            $used = $service->total - $service->remaining;
                            $progress_percentage = ($used / $service->total) * 100;
                            
                            // Progress bar renk sınıfını belirle
                            $progressClass = match(true) {
                                $progress_percentage <= 10 => 'progress-error',
                                $progress_percentage <= 50 => 'progress-warning',
                                $progress_percentage < 100 => 'progress-primary',
                                default => 'progress-success'
                            };
                        @endphp
                        
                        <div class="card bg-base-100 shadow-sm group hover:shadow-md transition-all duration-300">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <span class="text-xl">⭐️</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-semibold text-lg">{{ $service->service->name }}</h3>
                                            <span class="badge badge-primary">{{ $service->remaining }} / {{ $service->total }}</span>
                                        </div>
                                        <p class="text-sm text-base-content/60">{{ $service->service->category->name ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- DaisyUI Progress Bar with Dynamic Color -->
                                <progress class="progress {{ $progressClass }} w-full" 
                                    value="{{ $used }}" 
                                    max="{{ $service->total }}">
                                </progress>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Sağ Taraf - Tamamlanan Hizmetler -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold">{{ __('client.page_seans.sections.completed.title') }}</h2>
                        <span class="text-sm text-base-content/60">
                            {{ __('client.page_seans.sections.completed.count', ['count' => $data->where('remaining', 0)->count()]) }}
                        </span>
                    </div>

                    @foreach ($data->where('remaining', 0) as $service)
                        <div class="card bg-base-100/50 shadow-sm">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-success/10 flex items-center justify-center">
                                        <span class="text-lg">✨</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium">{{ $service->service->name }}</h3>
                                        <p class="text-sm text-base-content/60">{{ $service->service->category->name ?? '-' }}</p>
                                    </div>
                                    <div class="text-sm text-base-content/60">
                                        {{ __('client.page_seans.service.sessions', ['count' => $service->total]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>