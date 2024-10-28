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
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast, WithPagination;

    public $client;

    public $client_offers = null;

    public bool $offer_edit = false;

    public ?Offer $selected_offer;

    public $view = 'list';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function headers()
    {
        //OfferStatus::class;
        //LiveHelper::price_text()
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

    public function with()
    {
        return [
            'offers' => $this->getOffers(),
            'headers' => $this->headers(),
        ];
    }

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
};

?>
<div>
    <div class="flex justify-end mb-4 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
            icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline" />
    </div>
    @if ($view == 'table')
    <div>
    <x-card title="">
        <x-table :headers="$headers" :rows="$offers" wire:model="expanded" :sort-by="$sortBy" striped with-pagination
            expandable>
            <x-slot:empty>
                <x-icon name="o-cube" label="Teklif bulunmuyor." />
            </x-slot:empty>
            @scope('cell_price', $offer)
            {{ LiveHelper::price_text($offer->price) }}
            @endscope
            @can('offer_process')
            @scope('actions', $offer)
            <div class="flex">
                @if ($offer->status == OfferStatus::waiting)

                <x-button icon="o-x-circle" responsive wire:click="cancelOffer({{ $offer->id }})"
                    wire:confirm="Teklifi iptal etmek istediğinizden emin misiniz ?" tooltip="İptal et" spinner
                    class="btn-sm text-red-600" />
                

                @endif
                @if ($offer->status != OfferStatus::success)
                <x-button icon="o-check" responsive wire:click="approveOffer({{ $offer->id }})" tooltip="Onayla"
                    wire:confirm="Teklifi onaylamak istediğinizden emin misiniz ? Onayladığınızda hizmetler işlenecektir, kasa işlemi yapılmayacaktır. Ödeme aldıysanız tahsilat bölümünden işlem yapın."
                    spinner class="btn-sm text-green-600" />
                <livewire:admin.actions.offer.edit_offer :$offer :key="rand(10000000,99999999)" />
                @endif
            </div>
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
            <x-badge :value="$offer->status->label()" class="badge-{{ $offer->status->color() }}" />
            @endscope
        </x-table>
    </x-card>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if ($offers->count() == 0)
        <p class="text-center">Tekjlif bulunmuyor.</p>
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
                <x-badge :value="$offer->status->label()" class="badge-{{ $offer->status->color() }}" />
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
        <x:slot:menu>
            <x-button icon="o-x-circle" responsive wire:click="cancelOffer({{ $offer->id }})"
                wire:confirm="Teklifi iptal etmek istediğinizden emin misiniz ?" tooltip="İptal et" spinner
                class="btn-sm text-red-600" />
            @if ($offer->status != OfferStatus::success)
            <x-button icon="o-check" responsive wire:click="approveOffer({{ $offer->id }})" tooltip="Onayla"
                wire:confirm="Teklifi onaylamak istediğinizden emin misiniz ? Onayladığınızda hizmetler işlenecektir, kasa işlemi yapılmayacaktır. Ödeme aldıysanız tahsilat bölümünden işlem yapın."
                spinner class="btn-sm text-green-600" />
            <livewire:admin.actions.offer.edit_offer :$offer wire:key="rand(100000000,999999999)" />
            @endif
        </x:slot:menu>
    </x-card>
    @endforeach
    </div>
    <x-pagination :rows="$offers" />
    @endif
</div>