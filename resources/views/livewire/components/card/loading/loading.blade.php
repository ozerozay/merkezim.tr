<div class="relative min-h-[200px] flex items-center justify-center p-6">
    <!-- Ana Loading Container -->
    <div class="relative bg-base-100 rounded-[32px] border border-base-200 p-8 max-w-sm w-full">
        <!-- Animasyonlu Arka Plan -->
        <div class="absolute inset-0 rounded-[32px] animate-pulse bg-primary/5"></div>
        
        <!-- Loading İçeriği -->
        <div class="relative z-10 flex flex-col items-center gap-6">
            <!-- Özel Spinner -->
            <div class="relative">
                <div class="w-20 h-20">
                    <!-- Loading Indicator -->
                    <div class="absolute inset-0 rounded-full border-4 border-primary/10"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-primary border-t-transparent animate-spin"></div>
                </div>
                <!-- Merkez Emoji -->
                <div class="absolute inset-0 flex items-center justify-center">
                    @php
                        $emojis = ['✨', '💆‍♀️', '💅', '💇‍♀️', '🧖‍♀️', '💫', '🌟', '⭐️', '🌺'];
                        $randomEmoji = $emojis[array_rand($emojis)];
                    @endphp
                    <span class="text-4xl animate-pulse">{{ $randomEmoji }}</span>
                </div>
            </div>

            <!-- Loading Metni -->
            <div class="text-center space-y-2">
                <h3 class="text-lg font-medium text-base-content">
                    {{ __('client.loading_overlay_message') }}
                </h3>
                <p class="text-sm text-base-content/60">
                    {{ __('client.page_seans.loading') }}
                </p>
            </div>
        </div>
    </div>
</div>
