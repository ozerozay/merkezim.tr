<?php

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public User $user;

    #[Url(keep: true)]
    public $tab = 'home';

    public $loadedComponents = [];

    public function renderComponent($tab)
    {
    }
};
?>

<div>
    <x-slot:title>
        {{ $user->name ?? '' }}
    </x-slot:title>
    <x-header separator progress-indicator>
        <x-slot:title>
            <x-avatar :image="$user->avatar" class="!w-14">

                <x-slot:placeholder>
                    <p class="text-xlg">{{ substr($user->name, 0, 1) }}</p>
                </x-slot:placeholder>
                <x-slot:title class="text-3xl pl-1">
                    {{ $user->name }}
                </x-slot:title>

            </x-avatar>
        </x-slot:title>
        <x-slot:subtitle>
            {{ $user->phone ?? '' }} - {{ $user->client_branch->name ?? '' }} - {{ $user->unique_id }}
        </x-slot:subtitle>
        <x-slot:actions>
            <x-button wire:click="$dispatch('spotlight.toggle')" label="⚙️ İŞLEMLER" class="btn-primary" />

            @if (1 == 2)
                <x-dropdown label="İŞLEMLER" responsive icon="o-cog-6-tooth" class="btn-primary" right>
                    <x-menu-item icon="tabler.edit" title="Bilgilerini Düzenle" />
                    @can('action_client_add_label')
                        <x-menu-item icon="tabler.tag-starred" title="Etiket Belirle"
                                     link="{{ route('admin.actions.client_add_label', ['client' => $user->id]) }}" />
                    @endcan
                    @can('action_client_add_note')
                        <x-menu-item icon="tabler.notes" title="Not Al"
                                     wire:click="$dispatch('slide-over.open', {component: 'actions.add-note', arguments: {'client': 1}})" />
                    @endcan
                    <x-menu-item icon="tabler.building-warehouse" title="Şube Değiştir"
                                 link="{{ route('admin.actions.client_note_add', ['client' => $user->id]) }}" />
                    <x-menu-separator />
                    <x-menu-sub title="Hizmet" icon="o-plus">
                        @can('action_client_create_service')
                            <x-menu-item icon="o-plus" title="Hizmet Yükle"
                                         link="{{ route('admin.actions.client_create_service', ['client' => $user->id]) }}" />
                        @endcan
                        @can('action_client_use_service')
                            <x-menu-item icon="tabler.minus" title="Hizmet Kullandır"
                                         link="{{ route('admin.actions.client_use_service', ['client' => $user->id]) }}" />
                        @endcan
                        @can('action_client_transfer_service')
                            <x-menu-item icon="tabler.transfer" title="Hizmet Aktar"
                                         link="{{ route('admin.actions.client_transfer_service', ['client' => $user->id]) }}" />
                        @endcan
                    </x-menu-sub>
                    <x-menu-separator />
                    <x-menu-sub title="Satış" icon="tabler.brand-mastercard">
                        @can('action_client_sale')
                            <x-menu-item icon="o-banknotes" title="Hizmet"
                                         link="{{ route('admin.actions.client_sale_create', ['client' => $user->id]) }}" />
                        @endcan
                        @can('action_adisyon_create')
                            <x-menu-item icon="o-banknotes" title="Adisyon"
                                         link="{{ route('admin.actions.adisyon_create', ['client' => $user->id]) }}" />
                        @endcan
                        @can('action_client_product_sale')
                            <x-menu-item icon="o-banknotes" title="Ürün"
                                         link="{{ route('admin.actions.client_product_sale', ['client' => $user->id]) }}" />
                        @endcan
                    </x-menu-sub>
                    <x-menu-separator />
                    <x-menu-sub title="Oluştur" icon="o-plus">
                        @can('action_client_create_taksit')
                            <x-menu-item icon="tabler.cash-banknote"
                                         link="{{ route('admin.actions.client_create_taksit', ['client' => $user->id]) }}"
                                         title="Taksit Oluştur" />
                        @endcan

                        @can('action_client_create_offer')
                            <x-menu-item icon="tabler.confetti"
                                         link="{{ route('admin.actions.client_create_offer', ['client' => $user->id]) }}"
                                         title="Teklif Oluştur" />
                        @endcan
                        @can('action_create_coupon')
                            <x-menu-item icon="tabler.gift-card"
                                         link="{{ route('admin.actions.create_coupon', ['client' => $user->id]) }}"
                                         title="Kupon Oluştur" />
                        @endcan
                        <x-menu-item icon="tabler.calendar-plus" title="Randevu Oluştur" />
                        <x-menu-item icon="tabler.help-hexagon" title="Destek Oluştur" />
                    </x-menu-sub>
                    <x-menu-separator />
                    <x-menu-sub title="İletişim" icon="tabler.brand-whatsapp">
                        <x-menu-item icon="tabler.device-mobile-message" title="SMS" />
                        <x-menu-item icon="tabler.brand-whatsapp" title="Whatsapp Mesajı" />
                    </x-menu-sub>
                    <x-menu-separator />
                    <x-menu-item @click.stop="">
                        <x-toggle label="Engelle" right />
                    </x-menu-item>
                </x-dropdown>
            @endif
        </x-slot:actions>
    </x-header>

    <x-tabs wire:model.live="tab" class="tabs-boxed" active-class="bg-primary rounded text-white"
            label-class="font-semibold" label-div-class="bg-primary/5 p-2 rounded">
        <x-tab name="home" label="🏠 Anasayfa">
            @if ($tab == 'home')
                <livewire:admin.client.profil.anasayfa />
            @endif
        </x-tab>
        <x-tab name="service" label="💼 Hizmet">
            @can('client_profil_service')
                @if ($tab == 'service')
                    <livewire:admin.client.profil.pages.service :client="$user->id" lazy />
                @endif
            @endcan
        </x-tab>
        @can('client_profil_sale')
            <x-tab name="sale" label="🛒 Satış">
                @if ($tab == 'sale')
                    <livewire:admin.client.profil.pages.sale :client="$user->id" lazy />
                @endif
            </x-tab>
        @endcan
        @can('client_profil_taksit')
            <x-tab name="taksit" label="💳 Taksit">
                @if ($tab == 'taksit')
                    <livewire:admin.client.profil.pages.taksit :client="$user->id" lazy />
                @endif
            </x-tab>
        @endcan
        @can('client_profil_appointment')
            <x-tab name="appointment" label="📅 Randevu">
                @if ($tab == 'appointment')
                    <livewire:admin.client.profil.pages.appointment :client="$user->id" lazy />
                @endif
            </x-tab>
        @endcan
        <x-tab name="product" label="📦 Ürün">
            @can('client_profil_product')
                @if ($tab == 'product')
                    <livewire:admin.client.profil.pages.product :client="$user->id" lazy />
                @endif
            @endcan
        </x-tab>
        @can('client_profil_adisyon')
            <x-tab name="adisyon" label="📋 Adisyon">
                @if ($tab == 'adisyon')
                    <livewire:admin.client.profil.pages.adisyon :client="$user->id" lazy />
                @endif
            </x-tab>
        @endcan
        @can('client_profil_offer')
            <x-tab name="offer" label="📜 Teklif">
                @if ($tab == 'offer')
                    <livewire:admin.client.profil.pages.offer :client="$user->id" lazy />
                @endif
            </x-tab>
        @endcan
        @can('client_profil_coupon')
            <x-tab name="coupon" label="🎟️ Kupon">
                @if ($tab == 'coupon')
                    <livewire:admin.client.profil.pages.coupon :client="$user->id" lazy />
                @endif
            </x-tab>
        @endcan
        @can('client_profil_note')
            <x-tab name="note" label="📝 Not">
                @if ($tab == 'note')
                    <livewire:admin.client.profil.pages.note :client="$user->id" lazy />
                @endif
            </x-tab>
        @endcan
    </x-tabs>


</div>
