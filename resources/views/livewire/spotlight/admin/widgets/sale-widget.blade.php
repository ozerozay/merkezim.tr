<div class="relative text-base-content p-2 min-h-[200px]">
    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <span class="loading loading-spinner loading-md text-primary"></span>
                <span class="text-sm text-base-content/70">Yükleniyor...</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="h-full flex flex-col">
        <!-- Header Section -->
        <div class="mb-3">
            <div class="flex flex-col gap-2">
                <!-- Title and Actions Row -->
                <div class="flex flex-row justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-primary/10 rounded-lg">
                            <i class="text-lg text-primary">💰</i>
                        </div>
                        <h2 class="text-base font-bold">Satışlar</h2>
                    </div>

                    <div class="flex items-center gap-2">
                        <select wire:model.live="selectedDateRange" 
                                class="select select-sm select-bordered"
                                wire:loading.class="select-disabled"
                                wire:target="loadData">
                            @foreach($dateRangeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Branch Selection Row -->
                @if(count($branches) > 1)
                    <div class="flex justify-end">
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-sm normal-case flex items-center gap-2 min-w-[140px] sm:min-w-[180px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="flex-1 text-left text-sm sm:text-base">
                                    {{ count($selectedBranches) }} Şube Seçili
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </label>
                            <div tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 rounded-box w-56 mt-1">
                                <div class="py-2">
                                    @foreach ($branches as $branch)
                                        <div class="form-control">
                                            <label class="label cursor-pointer hover:bg-base-200 rounded-lg -mx-1 px-1">
                                                <span class="label-text">{{ $branch['name'] }}</span>
                                                <input type="checkbox" 
                                                       class="checkbox checkbox-sm"
                                                       wire:model.live="selectedBranches" 
                                                       value="{{ $branch['id'] }}"
                                                       wire:change="$refresh">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Branch Cards -->
        <div class="flex-1 overflow-y-auto">
            <div class="grid grid-cols-1 gap-2">
                @foreach ($selectedBranchesData as $branch)
                    <div class="card bg-base-100 w-full">
                        <div class="card-body p-2.5">
                            <!-- Branch Header -->
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="avatar placeholder">
                                        <div class="w-7 h-7 rounded-lg bg-primary/10 flex items-center justify-center">
                                            <span class="text-primary text-base">🏢</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium">{{ $branch['name'] }}</h3>
                                        <p class="text-xs opacity-50">Son güncelleme: {{ now()->toDateString() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <div class="bg-base-200 rounded-lg p-2">
                                    <div class="text-xs opacity-50">Tutar</div>
                                    <div class="text-success text-sm font-medium">
                                        {{ number_format($branch['total_sales'], 2) }}₺
                                    </div>
                                </div>
                                <div class="bg-base-200 rounded-lg p-2">
                                    <div class="text-xs opacity-50">Adet</div>
                                    <div class="text-primary text-sm font-medium">
                                        {{ $branch['successful_sales_count'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary Section -->
            <div class="mt-2 border-t border-base-200 pt-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <div class="avatar placeholder">
                            <div class="w-6 h-6 rounded-lg bg-primary/10 flex items-center justify-center">
                                <span class="text-primary text-sm">📊</span>
                            </div>
                        </div>
                        <span class="text-xs opacity-70">Toplam:</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs opacity-50">Tutar:</span>
                            <span class="text-success text-sm font-medium">
                                {{ number_format(collect($selectedBranchesData)->sum('total_sales'), 2) }}₺
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs opacity-50">Adet:</span>
                            <span class="text-primary text-sm font-medium">
                                {{ collect($selectedBranchesData)->sum('successful_sales_count') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
