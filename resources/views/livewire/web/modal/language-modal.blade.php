<div>
    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
        <span>🌍</span>
        Dil Seçimi / Language Selection
    </h3>

    <div class="grid grid-cols-1 gap-3">
        <!-- Türkçe -->
        <a href="{{ route('client.index') }}?lang=tr"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-base-200 transition-colors {{ app()->getLocale() === 'tr' ? 'bg-primary/10 text-primary' : '' }}">
            <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                <span class="text-2xl">🇹🇷</span>
            </div>
            <div class="flex-1">
                <div class="font-medium">Türkçe</div>
                <div class="text-xs opacity-60">Turkish</div>
            </div>
            @if(app()->getLocale() === 'tr')
            <div class="text-primary">
                <x-icon name="o-check-circle" class="w-5 h-5" />
            </div>
            @endif
        </a>

        <!-- English -->
        <a href="{{ route('client.index') }}?lang=en"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-base-200 transition-colors {{ app()->getLocale() === 'en' ? 'bg-primary/10 text-primary' : '' }}">
            <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                <span class="text-2xl">🇬🇧</span>
            </div>
            <div class="flex-1">
                <div class="font-medium">English</div>
                <div class="text-xs opacity-60">İngilizce</div>
            </div>
            @if(app()->getLocale() === 'en')
            <div class="text-primary">
                <x-icon name="o-check-circle" class="w-5 h-5" />
            </div>
            @endif
        </a>
    </div>
</div>