<div>
<div class="flex flex-col">
  
    <!-- Ana Menü -->
    <div class="bg-base-100 p-2 mt-2 rounded-2xl">
        <div class="grid grid-cols-1 gap-0.5 text-sm">
            <!-- Ana Sayfa -->
            <a href="{{ route('client.index') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.index') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                <span class="text-lg">🏠</span>
                <span>Anasayfa</span>
            </a>

          
            @if ($this->checkSetting(\App\Enum\SettingsType::client_page_seans->name))
                <a href="{{ route('client.profil.seans') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.profil.seans*') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                    <span class="text-lg">✨</span>
                    <span>{{ __('client.menu_seans') }}</span>
                </a>
            @endif

            @if ($this->checkSetting(\App\Enum\SettingsType::client_page_appointment->name))
                <a href="{{ route('client.profil.appointment') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.profil.appointment*') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                    <span class="text-lg">📅</span>
                    <span>{{ __('client.menu_appointment') }}</span>
                </a>
            @endif

            @if ($this->checkSetting(\App\Enum\SettingsType::client_page_taksit->name))
                <a href="{{ route('client.profil.taksit') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.profil.taksit*') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                    <span class="text-lg">💳</span>
                    <span>{{ __('client.menu_payments') }}</span>
                </a>
            @endif

            @if ($this->checkSetting(\App\Enum\SettingsType::client_page_offer->name))
                <a href="{{ route('client.profil.offer') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.profil.offer*') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                    <span class="text-lg">🎯</span>
                    <span>{{ __('client.menu_offer') }}</span>
                </a>
            @endif

            @if ($this->checkSetting(\App\Enum\SettingsType::client_page_coupon->name))
                <a href="{{ route('client.profil.coupon') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.profil.coupon*') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                    <span class="text-lg">🎟️</span>
                    <span>{{ __('client.menu_coupon') }}</span>
                </a>
            @endif

            @if ($this->checkSetting(\App\Enum\SettingsType::client_page_referans->name))
                <a href="{{ route('client.profil.invite') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.profil.invite*') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                    <span class="text-lg">🎁</span>
                    <span>{{ __('client.menu_referans') }}</span>
                </a>
            @endif

            @if ($this->checkSetting(\App\Enum\SettingsType::client_page_earn->name))
                <a href="{{ route('client.profil.earn') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.profil.earn*') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                    <span class="text-lg">💎</span>
                    <span>{{ __('client.menu_earn') }}</span>
                </a>
            @endif

              <!-- Bize Ulaşın -->
              <a href="{{ route('client.contact') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.contact') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                <span class="text-lg">📞</span>
                <span>Bize Ulaşın</span>
            </a>


            <a href="{{ route('logout') }}" class="flex items-center gap-2.5 p-1.5 mt-1 rounded-xl hover:bg-error/10 hover:text-error transition-all duration-300 text-base-content/70">
                <span class="text-lg">🚪</span>
                <span>{{ __('client.menu_logout') }}</span>
            </a>

            
        </div>
        
    </div>
    
</div>
<div class="bg-base-100 rounded-xl border border-base-200">
    <div class="p-4">
        <div class="flex items-center justify-between">
            <!-- Dil Seçimi -->
            <button wire:click="$dispatch('modal.open', {component: 'web.modal.language-modal'})" 
        class="btn btn-ghost btn-sm normal-case gap-2">
    <div class="w-6 h-6 rounded-full bg-base-200 flex items-center justify-center">
        <span class="text-base">{{ app()->getLocale() === 'tr' ? '🇹🇷' : '🇬🇧' }}</span>
    </div>
    <span class="hidden sm:inline text-xs font-medium">
        {{ app()->getLocale() === 'tr' ? 'Türkçe' : 'English' }}
    </span>
    <span class="text-lg">🌐</span>
</button>


            <!-- Tema Değiştirici -->
            <x-theme-toggle class="btn btn-ghost btn-sm">
                <span class="text-base">🌙</span>
            </x-theme-toggle>
        </div>
    </div>
</div>

</div>