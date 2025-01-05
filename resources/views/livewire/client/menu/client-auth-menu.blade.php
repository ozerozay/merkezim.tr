<div class="flex flex-col">
    <!-- Profil Kartı -->
    <div class="bg-base-100/50 backdrop-blur-sm rounded-2xl border border-base-200 p-3">
        <div class="flex items-center justify-between">
            <!-- Sol: Avatar ve Kullanıcı Bilgileri -->
            <div class="flex items-center gap-3">
                <!-- Avatar -->
                <div class="relative">
                    @if(auth()->user()->avatar)
                        <img class="w-12 h-12 rounded-xl object-cover" 
                             src="{{ auth()->user()->avatar }}" 
                             alt="{{ auth()->user()->name }}">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="text-xl text-primary font-medium">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-success rounded-full border-2 border-base-100"></div>
                </div>

                <!-- Kullanıcı Bilgileri -->
                <div class="flex flex-col">
                    <span class="font-medium text-base-content">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="text-sm text-base-content/70">
                        {{ auth()->user()->client_branch->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Marge Shop - Canlı Tasarım -->
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_appointment->name))
        <a href="{{ route('client.shop.packages') }}" class="flex items-center gap-3 p-2.5 mt-2 rounded-2xl bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 transition-all duration-300 border border-primary/20 shadow-lg shadow-primary/20">
            <span class="text-xl">✨</span>
            <div class="flex flex-col">
                <span class="font-medium text-white">{{ __('client.menu_shop') }}</span>
                <span class="text-xs text-white/90">Özel hizmet paketleri</span>
            </div>
        </a>
    @endif

    <!-- Ana Menü -->
    <div class="bg-base-100 p-2 mt-2 rounded-2xl border border-base-200">
        <div class="grid grid-cols-1 gap-0.5 text-sm">
            <!-- Ana Sayfa -->
            <a href="{{ route('client.index') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.index') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                <span class="text-lg">🏠</span>
                <span>Anasayfa</span>
            </a>

            <!-- Bize Ulaşın -->
            <a href="{{ route('client.contact') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-base-200 transition-all duration-300 {{ request()->routeIs('client.contact') ? 'text-primary font-medium' : 'text-base-content/70' }}">
                <span class="text-lg">📞</span>
                <span>Bize Ulaşın</span>
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

            <a href="{{ route('logout') }}" class="flex items-center gap-2.5 p-1.5 mt-1 rounded-xl hover:bg-error/10 hover:text-error transition-all duration-300 text-base-content/70">
                <span class="text-lg">🚪</span>
                <span>{{ __('client.menu_logout') }}</span>
            </a>
        </div>
    </div>
</div>
