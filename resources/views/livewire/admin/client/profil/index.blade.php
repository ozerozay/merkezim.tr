<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Danışan')]
class extends Component {
    use Toast;

    public User $user;

    #[Url(keep: true)]
    public $tab = 'home';

    public $loadedComponents = [];

    public function updatedTab()
    {
        $this->success($this->tab);
    }

    public function renderComponent($tab)
    {
    }

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
    <x-header separator progress-indicator>
        <x-slot:title>
            {{ $user->name ?? '' }}
        </x-slot:title>
        <x-slot:subtitle>
            {{ $user->phone ?? '' }} - {{ $user->client_branch->name ?? '' }}
        </x-slot:subtitle>
        <x-slot:middle class="!justify-end">

        </x-slot:middle>
        <x-slot:actions>
            <x-dropdown label="İŞLEMLER" responsive icon="o-cog-6-tooth" class="btn-primary" right>
                <x-menu-item icon="tabler.edit" title="Bilgilerini Düzenle"/>
                @can('action_client_add_label')
                    <x-menu-item icon="tabler.tag-starred" title="Etiket Belirle"
                                 link="{{ route('admin.actions.client_add_label', ['client' => $user->id]) }}"/>
                @endcan
                @can('action_client_add_note')
                    <x-menu-item icon="tabler.notes" title="Not Al"
                                 link="{{ route('admin.actions.client_note_add', ['client' => $user->id]) }}"/>
                @endcan
                <x-menu-item icon="tabler.building-warehouse" title="Şube Değiştir"
                             link="{{ route('admin.actions.client_note_add', ['client' => $user->id]) }}"/>
                <x-menu-separator/>
                <x-menu-sub title="Hizmet" icon="o-plus">
                    @can('action_client_create_service')
                        <x-menu-item icon="o-plus" title="Hizmet Yükle"
                                     link="{{ route('admin.actions.client_create_service', ['client' => $user->id]) }}"/>
                    @endcan
                    @can('action_client_use_service')
                        <x-menu-item icon="tabler.minus" title="Hizmet Kullandır"
                                     link="{{ route('admin.actions.client_use_service', ['client' => $user->id]) }}"/>
                    @endcan
                    @can('action_client_transfer_service')
                        <x-menu-item icon="tabler.transfer" title="Hizmet Aktar"
                                     link="{{ route('admin.actions.client_transfer_service', ['client' => $user->id]) }}"/>
                    @endcan
                </x-menu-sub>
                <x-menu-separator/>
                <x-menu-sub title="Satış" icon="tabler.brand-mastercard">
                    @can('action_client_sale')
                        <x-menu-item icon="o-banknotes" title="Hizmet"
                                     link="{{ route('admin.actions.client_sale_create', ['client' => $user->id]) }}"/>
                    @endcan
                    @can('action_adisyon_create')
                        <x-menu-item icon="o-banknotes" title="Adisyon"
                                     link="{{ route('admin.actions.adisyon_create', ['client' => $user->id]) }}"/>
                    @endcan
                    @can('action_client_product_sale')
                        <x-menu-item icon="o-banknotes" title="Ürün"
                                     link="{{ route('admin.actions.client_product_sale', ['client' => $user->id]) }}"/>
                    @endcan
                </x-menu-sub>
                <x-menu-separator/>
                <x-menu-sub title="Oluştur" icon="o-plus">
                    @can('action_client_create_taksit')
                        <x-menu-item icon="tabler.cash-banknote"
                                     link="{{ route('admin.actions.client_create_taksit', ['client' => $user->id]) }}"
                                     title="Taksit Oluştur"/>
                    @endcan

                    @can('action_client_create_offer')
                        <x-menu-item icon="tabler.confetti"
                                     link="{{ route('admin.actions.client_create_offer', ['client' => $user->id]) }}"
                                     title="Teklif Oluştur"/>
                    @endcan
                    @can('action_create_coupon')
                        <x-menu-item icon="tabler.gift-card"
                                     link="{{ route('admin.actions.create_coupon', ['client' => $user->id]) }}"
                                     title="Kupon Oluştur"/>
                    @endcan
                    <x-menu-item icon="tabler.calendar-plus" title="Randevu Oluştur"/>
                    <x-menu-item icon="tabler.help-hexagon" title="Destek Oluştur"/>
                </x-menu-sub>
                <x-menu-separator/>
                <x-menu-sub title="İletişim" icon="tabler.brand-whatsapp">
                    <x-menu-item icon="tabler.device-mobile-message" title="SMS"/>
                    <x-menu-item icon="tabler.brand-whatsapp" title="Whatsapp Mesajı"/>
                </x-menu-sub>
                <x-menu-separator/>
                <x-menu-item @click.stop="">
                    <x-toggle label="Engelle" right/>
                </x-menu-item>
            </x-dropdown>
        </x-slot:actions>
    </x-header>

    <x-tabs wire:model.live="tab" class="tabs-boxed" active-class="bg-primary rounded text-white"
            label-class="font-semibold" label-div-class="bg-primary/5 p-2 rounded">
        <x-tab name="home" label="Anasayfa">
            @if ($tab == 'home')
                <livewire:admin.client.profil.anasayfa/>
            @endif
        </x-tab>
        <x-tab name="service" label="Hizmet">
            @can('client_profil_service')
                <x-tab name="service" label="Hizmet">
                    @if ($tab == 'service')
                        <livewire:admin.client.profil.pages.service :client="$user->id" lazy/>
                    @endif
                </x-tab>
            @endcan
        </x-tab>
        @can('client_profil_sale')
            <x-tab name="sale" label="Satış">
                @if ($tab == 'sale')
                    <livewire:admin.client.profil.pages.sale :client="$user->id" lazy/>
                @endif
            </x-tab>
        @endcan
        @can('client_profil_taksit')
            <x-tab name="taksit" label="Taksit">
                @if ($tab == 'taksit')
                    <livewire:admin.client.profil.pages.taksit :client="$user->id" lazy/>
                @endif
            </x-tab>
        @endcan
        @can('client_profil_appointment')
            <x-tab name="appointment" label="Randevu">
                @if ($tab == 'appointment')
                    <livewire:admin.client.profil.pages.appointment :client="$user->id" lazy/>
                @endif
            </x-tab>
        @endcan
        <x-tab name="product" label="Ürün">
            @can('client_profil_product')
                <x-tab name="product" label="Ürün">
                    @if ($tab == 'product')
                        <livewire:admin.client.profil.pages.product :client="$user->id" lazy/>
                    @endif
                </x-tab>
            @endcan
        </x-tab>
        @can('client_profil_adisyon')
            <x-tab name="adisyon" label="Adisyon">

                <x-tab name="adisyon" label="Adisyon">
                    @if ($tab == 'adisyon')
                        <livewire:admin.client.profil.pages.adisyon :client="$user->id" lazy/>
                    @endif
                </x-tab>

            </x-tab>
        @endcan
        @can('client_profil_offer')
            <x-tab name="offer" label="Teklif">
                @if ($tab == 'offer')
                    <livewire:admin.client.profil.pages.offer :client="$user->id" lazy/>
                @endif
            </x-tab>
        @endcan
        <x-tab name="tab-kupon" label="Kupon">
            <div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Aktif Kupon -->
                <div class="card bg-green-100 shadow-xl">
                    <div class="card-body flex flex-col">
                        <h2 class="card-title text-green-600">%25 İNDİRİM KUPONU</h2>
                        <p class="text-green-500">Kupon kodu: <span class="font-mono">SAVE20</span></p>
                        <p class="text-gray-600">Bitiş: 2024-12-31</p>
                        <p class="text-sm text-gray-500">John Doe</p>
                        <div class="card-actions justify-end">
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Süresi Dolmuş Kupon -->
                <div class="card bg-red-100 shadow-xl">
                    <div class="card-body flex flex-col">
                        <h2 class="card-title text-red-600">10% Off on Clothing</h2>
                        <p class="text-red-500">Kupon kodu: <span class="font-mono">SAVE10</span></p>
                        <p class="text-gray-600">Son kullanma tarihi: 2023-08-31</p>
                        <p class="text-sm text-gray-500">Oluşturan: Jane Smith</p>
                        <div class="card-actions justify-end">
                            <span class="badge badge-error">Süresi Doldu</span>
                        </div>
                    </div>
                </div>

                <!-- Kullanılmış Kupon -->
                <div class="card bg-gray-100 shadow-xl">
                    <div class="card-body flex flex-col">
                        <h2 class="card-title text-gray-600">15% Off on Accessories</h2>
                        <p class="text-gray-500">Kupon kodu: <span class="font-mono">SAVE15</span></p>
                        <p class="text-gray-600">Son kullanma tarihi: 2024-03-15</p>
                        <p class="text-sm text-gray-500">Oluşturan: Sarah Lee</p>
                        <div class="card-actions justify-end">
                            <span class="badge badge-neutral">Kullanıldı</span>
                        </div>
                    </div>
                </div>

                <div class="card bg-green-100 shadow-xl">
                    <div class="card-body flex flex-col">
                        <h2 class="card-title text-green-600">%25 İNDİRİM KUPONU</h2>
                        <p class="text-green-500">Kupon kodu: <span class="font-mono">SAVE20</span></p>
                        <p class="text-gray-600">Son kullanma tarihi: 2024-12-31</p>
                        <p class="text-sm text-gray-500">Oluşturan: John Doe</p>
                        <div class="card-actions justify-end">
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Süresi Dolmuş Kupon -->
                <div class="card bg-red-100 shadow-xl">
                    <div class="card-body flex flex-col">
                        <h2 class="card-title text-red-600">10% Off on Clothing</h2>
                        <p class="text-red-500">Kupon kodu: <span class="font-mono">SAVE10</span></p>
                        <p class="text-gray-600">Son kullanma tarihi: 2023-08-31</p>
                        <p class="text-sm text-gray-500">Oluşturan: Jane Smith</p>
                        <div class="card-actions justify-end">
                            <span class="badge badge-error">Süresi Doldu</span>
                        </div>
                    </div>
                </div>

                <!-- Kullanılmış Kupon -->
                <div class="card bg-gray-100 shadow-xl">
                    <div class="card-body flex flex-col">
                        <h2 class="card-title text-gray-600">15% Off on Accessories</h2>
                        <p class="text-gray-500">Kupon kodu: <span class="font-mono">SAVE15</span></p>
                        <p class="text-gray-600">Son kullanma tarihi: 2024-03-15</p>
                        <p class="text-sm text-gray-500">Oluşturan: Sarah Lee</p>
                        <div class="card-actions justify-end">
                            <span class="badge badge-neutral">Kullanıldı</span>
                        </div>
                    </div>
                </div>


            </div>

        </x-tab>
        @can('client_profil_note')
            <x-tab name="note" label="Not">
                @if ($tab == 'note')
                    <livewire:admin.client.profil.pages.note :client="$user->id" lazy/>
                @endif
            </x-tab>
        @endcan

    </x-tabs>

    <livewire:admin.client.actions.add_note wire:model="client_note"/>
    <livewire:admin.client.actions.add_coupon wire:model="client_coupon"/>


</div>
