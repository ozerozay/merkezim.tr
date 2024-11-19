<?php

use App\Actions\Client\GetClientOffers;
use App\Actions\Offer\ApproveOfferAction;
use App\Actions\Offer\CancelOfferAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Models\Offer;
use App\OfferStatus;
use App\Peren;
use App\Traits\LiveHelper;
use App\Traits\WithViewPlaceHolder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithPagination, WithViewPlaceHolder, \Livewire\WithoutUrlPagination;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    public function mount(): void
    {
        $this->sortBy = ['column' => 'created_at', 'direction' => 'asc'];
    }

    public function getData()
    {
        return GetClientOffers::run($this->client, true, $this->sortBy);
    }

    public function headers()
    {
        return [
            ['key' => 'unique_id', 'label' => 'ID', 'sortBy' => 'unique_id'],
            ['key' => 'price', 'label' => 'Fiyat', 'sortBy' => 'price'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
            ['key' => 'items_count', 'label' => 'Hizmet', 'sortable' => false],
            ['key' => 'date_human_created', 'label' => 'Oluşturulma', 'sortBy' => 'created_at'],
            ['key' => 'date_human_expire', 'label' => 'Bitiş', 'sortBy' => 'expire_date'],
            ['key' => 'user.name', 'label' => 'Personel', 'sortable' => false],
            ['key' => 'message', 'label' => 'Açıklama', 'sortBy' => 'message'],
        ];
    }

    public function getOffers(): LengthAwarePaginator
    {
        return GetClientOffers::run($this->client, true, $this->sortBy);
    }

    public function showSettings($id): void
    {
        $this->dispatch('drawer-offer-update-id', $id)->to('components.drawers.drawer_offer');
        $this->editing = true;
    }

    public function with()
    {
        return [
            'offers' => $this->getData(),
            'headers' => $this->headers(),
            'statistic' => [
                ['name' => 'Toplam', 'value' => 0, 'number' => true],
                ['name' => 'Kalan', 'value' => 0, 'number' => true],
                ['name' => 'Gecikmiş', 'value' => 0, 'number' => true, 'red' => true],
            ],
        ];
    }
    /*
        public function cancelOffer($id)
        {
            if (CheckUserInstantApprove::run(auth()->user()->id)) {
                CancelOfferAction::run($id);

                $this->success('Teklif iptal edildi.');
            } else {
                CreateApproveRequestAction::run($id, auth()->user()->id, ApproveTypes::cancel_offer, 'İptal');

                $this->success(Peren::$approve_request_ok);
            }
        }

        public function approveOffer($id)
        {
            if (CheckUserInstantApprove::run(auth()->user()->id)) {
                ApproveOfferAction::run($id);

                $this->success('Teklif onaylandı.');
            } else {
                CreateApproveRequestAction::run($id, auth()->user()->id, ApproveTypes::approve_offer, 'Onay');

                $this->success(Peren::$approve_request_ok);
            }
        }

        public function placeholder()
        {
            return view('livewire.components.card.loading.loading');
        }

        public function changeView()
        {
            $this->view = $this->view == 'table' ? 'list' : 'table';
        }

        public function editOffer($offer)
        {
            $this->selected_offer = Offer::find($offer);
            $this->offer_edit = true;
        }

        public array $expanded = [];
    */
};

?>
<div>
    <livewire:components.card.statistic.card_statistic :data="$statistic"/>
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
                  icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline"/>
    </div>
    @if ($view)
        <div>
            <x-card title="">
                <x-table :headers="$headers" :rows="$offers" wire:model="expanded" :sort-by="$sortBy" striped
                         with-pagination
                         expandable>
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Teklif bulunmuyor."/>
                    </x-slot:empty>
                    @scope('cell_price', $offer)
                    {{ LiveHelper::price_text($offer->price) }}
                    @endscope
                    @can('offer_process')
                        @scope('actions', $offer)
                        <x-button icon="tabler.settings"
                                  wire:click="showSettings({{ $offer->id }})"
                                  class="btn-circle btn-sm btn-primary"/>
                        @endscope
                    @endcan
                    @scope('expansion', $offer)
                    <div class="bg-base-200 p-8 font-bold">
                        @foreach ($offer->items as $item)
                            {{ $item->itemable->name }}({{ $item->quantity }}) -
                        @endforeach
                    </div>
                    @endscope
                    @scope('cell_status', $offer)
                    <x-badge :value="$offer->status->label()" class="badge-{{ $offer->status->color() }}"/>
                    @endscope
                </x-table>
            </x-card>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($offers->count() == 0)
                <p class="text-center">Teklif bulunmuyor.</p>
            @endif
            @foreach ($offers as $offer)
                <x-card title="{{ $offer->unique_id }}" separator class="mb-2">
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Fiyat
                        </x-slot:value>
                        <x-slot:actions>
                            {{ LiveHelper::price_text($offer->price) }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Oluşturan
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->user->name }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Durum
                        </x-slot:value>
                        <x-slot:actions>
                            <x-badge :value="$offer->status->label()" class="badge-{{ $offer->status->color() }}"/>
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Oluşturulma
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->date_human_created }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Bitiş
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->date_human_expire }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Kullanıcı
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->user->name }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Mesaj
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->message }}
                        </x-slot:actions>
                    </x-list-item>
                    <div class="bg-base-200 p-8 font-bold">
                        @foreach ($offer->items as $item)
                            {{ $item->itemable->name }}({{ $item->quantity }}) -
                        @endforeach
                    </div>
                    @can('offer_process')
                        <x-slot:menu>
                            <x-button icon="tabler.settings"
                                      wire:click="showSettings({{ $offer->id }})"
                                      class="btn-circle btn-sm btn-primary"/>
                        </x-slot:menu>
                    @endcan
                </x-card>
            @endforeach
        </div>
        <x-pagination :rows="$offers"/>
    @endif
    @can('offer_process')
        <livewire:components.drawers.drawer_offer wire:model="editing"/>
    @endcan

</div>
