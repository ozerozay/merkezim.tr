@if(!empty($notifications))
    <div class="px-4 py-3">
        @foreach ($notifications as $notification)
            <div wire:key="notification-{{ $notification['id'] }}"
                 x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 x-init="setTimeout(() => { 
                    show = false;
                    setTimeout(() => $wire.removeNotification('{{ $notification['id'] }}'), 300);
                 }, {{ $notification['duration'] ?? 6000 }})"
                 @class([
                    'flex items-start gap-3 p-4 rounded-xl border shadow-sm',
                    'bg-success-50/80 border-success-100 text-success-900 dark:bg-success-900/10 dark:border-success-900/20 dark:text-success-200' => $notification['type'] === 'success',
                    'bg-error-50/80 border-error-100 text-error-900 dark:bg-error-900/10 dark:border-error-900/20 dark:text-error-200' => $notification['type'] === 'error',
                    'bg-warning-50/80 border-warning-100 text-warning-900 dark:bg-warning-900/10 dark:border-warning-900/20 dark:text-warning-200' => $notification['type'] === 'warning',
                    'bg-info-50/80 border-info-100 text-info-900 dark:bg-info-900/10 dark:border-info-900/20 dark:text-info-200' => $notification['type'] === 'info',
                 ])>
                <!-- Sol Taraf: İkon ve Mesaj -->
                <div class="flex items-start gap-3 flex-1">
                    <!-- İkon -->
                    <div @class([
                        'flex items-center justify-center w-8 h-8 rounded-lg mt-0.5',
                        'bg-success-100 text-success-600 dark:bg-success-900/20 dark:text-success-400' => $notification['type'] === 'success',
                        'bg-error-100 text-error-600 dark:bg-error-900/20 dark:text-error-400' => $notification['type'] === 'error',
                        'bg-warning-100 text-warning-600 dark:bg-warning-900/20 dark:text-warning-400' => $notification['type'] === 'warning',
                        'bg-info-100 text-info-600 dark:bg-info-900/20 dark:text-info-400' => $notification['type'] === 'info',
                    ])>
                        <span class="text-lg">
                            @if($notification['type'] === 'success') ✅
                            @elseif($notification['type'] === 'error') ❌
                            @elseif($notification['type'] === 'warning') ⚠️
                            @else ℹ️
                            @endif
                        </span>
                    </div>

                    <!-- Mesaj -->
                    <div class="flex-1 pt-1">
                        <p class="text-sm font-medium leading-5">
                            {{ $notification['message'] }}
                        </p>
                    </div>
                </div>

                <!-- Kapat Butonu -->
                <button @click="show = false" 
                        @class([
                            'p-1.5 rounded-lg transition-colors mt-1',
                            'hover:bg-success-100/80 dark:hover:bg-success-900/20' => $notification['type'] === 'success',
                            'hover:bg-error-100/80 dark:hover:bg-error-900/20' => $notification['type'] === 'error',
                            'hover:bg-warning-100/80 dark:hover:bg-warning-900/20' => $notification['type'] === 'warning',
                            'hover:bg-info-100/80 dark:hover:bg-info-900/20' => $notification['type'] === 'info',
                        ])>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endif 