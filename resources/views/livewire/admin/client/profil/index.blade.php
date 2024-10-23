<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public User $user;

    #[Url(keep: true)]
    public $tab = 'home';

    public $loadedComponents = [];

    public function updatedTab()
    {
        //$this->success($this->tab);

    }

    public function renderComponent($tab) {}

    public ?User $client_note;

    public ?User $client_coupon;

    public function client_note_open()
    {
        $this->client_note = $this->user;
    }

    public function client_coupon_open()
    {
        $this->client_coupon = $this->user;
    }

    #[On('client-note-cancel')]
    #[On('client-note-saved')]
    #[On('client-coupon-cancel')]
    #[On('client-coupon-saved')]
    public function client_cancel()
    {
        $this->client_note = null;
        $this->client_coupon = null;
    }
};
?>

<div>
    <x-header seperator progress-indicator>
        <x-slot:title>
            {{ $user->name ?? '' }} - {{ $user->phone ?? '' }}
        </x-slot:title>
        <x-slot:middle class="!justify-end">

        </x-slot:middle>
        <x-slot:actions>
            <x-dropdown label="İŞLEMLER" responsive icon="o-cog-6-tooth" class="btn-primary" right>
                <x-menu-item icon="tabler.edit" title="Bilgilerini Düzenle" />
                @can('action_client_add_label')
                <x-menu-item icon="tabler.tag-starred" title="Etiket Belirle"
                    link="{{ route('admin.actions.client_add_label', ['client' => $user->id]) }}" />
                @endcan
                @can('action_client_add_note')
                <x-menu-item icon="tabler.notes" title="Not Al"
                    link="{{ route('admin.actions.client_note_add', ['client' => $user->id]) }}" />
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
                    <x-menu-item icon="tabler.cash-banknote" link="{{ route('admin.actions.client_create_taksit', ['client' => $user->id]) }}" title="Taksit Oluştur" />
                    @endcan
                    <x-menu-item icon="tabler.cash-banknote" title="Kilitli Taksit Oluştur" />
                    @can('action_create_coupon')
                    <x-menu-item icon="tabler.confetti" link="{{ route('admin.actions.client_create_offer', ['client' => $user->id]) }}" title="Teklif Oluştur" />
                    @endcan
                    @can('action_create_coupon')
                    <x-menu-item icon="tabler.gift-card" link="{{ route('admin.actions.create_coupon', ['client' => $user->id]) }}" title="Kupon Oluştur" />
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
        </x-slot:actions>
    </x-header>

    <x-tabs wire:model.live="tab" class="tabs-boxed" active-class="bg-primary rounded text-white"
        label-class="font-semibold" label-div-class="bg-primary/5 p-2 rounded">
        <x-tab name="home" label="Anasayfa">
            @if ($tab == 'home')
            <livewire:admin.client.profil.anasayfa />
            @endif
        </x-tab>
        <x-tab name="service" label="Hizmet">
            @if ($tab == 'service')
            <livewire:admin.client.profil.service :client_id="$user->id" />
            @endif
        </x-tab>
        <x-tab name="tab-satis" label="Satış">
            <div>Musics</div>
        </x-tab>
        <x-tab name="tab-taksit" label="Taksit">
            <div>Musics</div>
        </x-tab>
        <x-tab name="tab-randevu" label="Randevu">
            <div>Musics</div>
        </x-tab>
        <x-tab name="tab-teklif" label="Teklif">
            <div>Musics</div>
        </x-tab>
        <x-tab name="tab-kupon" label="Kupon">
            <div>Musics</div>
        </x-tab>
        <x-tab name="tab-not" label="Not">
            <div>Musics</div>
        </x-tab>
    </x-tabs>

    <livewire:admin.client.actions.add_note wire:model="client_note" />
    <livewire:admin.client.actions.add_coupon wire:model="client_coupon" />


</div>