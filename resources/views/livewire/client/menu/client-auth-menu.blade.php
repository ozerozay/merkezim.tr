<div>
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_appointment->name))
        <x-menu-item title="{{ __('client.menu_shop') }}"
                     class="underline font-bold text-white bg-green-500 hover:bg-green-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.circle-plus" link="{{ route('client.shop.packages') }}"/>
    @endif
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_seans->name))
        <x-menu-item title="{{ __('client.menu_seans') }}"
                     class="text-white bg-blue-500 hover:bg-blue-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.mood-check" link="{{ route('client.profil.seans') }}"/>
    @endif
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_appointment->name))
        <x-menu-item title="{{ __('client.menu_appointment') }}"
                     class="text-white bg-yellow-500 hover:bg-yellow-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.calendar" link="{{ route('client.profil.appointment') }}"/>
    @endif
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_taksit->name))
        <x-menu-item title="{{ __('client.menu_payments') }}"
                     class="text-white bg-purple-500 hover:bg-purple-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.file-invoice" link="{{ route('client.profil.taksit') }}"/>
    @endif
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_offer->name))
        <x-menu-item title="{{ __('client.menu_offer') }}"
                     class="text-white bg-red-500 hover:bg-red-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.confetti" link="{{ route('client.profil.offer') }}"/>
    @endif
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_coupon->name))
        <x-menu-item title="{{ __('client.menu_coupon') }}"
                     class="text-white bg-teal-500 hover:bg-teal-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.gift-card" link="{{ route('client.profil.coupon') }}"/>
    @endif
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_referans->name))
        <x-menu-item title="{{ __('client.menu_referans') }}"
                     class="text-white bg-indigo-500 hover:bg-indigo-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.user-plus" link="{{ route('client.profil.invite') }}"/>
    @endif

    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_earn->name))
        <x-menu-item title="{{ __('client.menu_earn') }}"
                     class="text-white bg-pink-500 hover:bg-pink-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.heart" link="{{ route('client.profil.earn') }}"/>
    @endif
    @if ($this->checkSetting(\App\Enum\SettingsType::client_page_fatura->name) && 1==2)
        <x-menu-item title="{{ __('client.menu_invoice') }}"
                     class="text-white bg-gray-500 hover:bg-gray-700 p-3 rounded-lg transition-all duration-300"
                     icon="tabler.file-invoice" link="{{ route('login') }}"/>
    @endif
</div>
