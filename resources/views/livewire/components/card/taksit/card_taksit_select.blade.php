<?php

use App\Actions\Client\GetClientByUniqueID;
use App\Actions\Service\GetServicesAction;
use App\Actions\User\UserBranchOrClientBranchAction;
use App\Traits\LiveHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public ?Collection $selected_taksits;

    public $client = null;

    public $branch_ids = [];

    public $gender = 0;

    public $client_model = null;

    public $show_product_modal = false;

    public $service_collection = [];

    public $taksit_id = null;

    public function mount()
    {

        LiveHelper::class;

        $this->init();
    }

    public function init()
    {
        $this->client_model = GetClientByUniqueID::run(null, $this->client);
        if ($this->client_model) {
            $this->gender = $this->client_model?->gender == 1 ? 1 : 2;
            $this->branch_ids = UserBranchOrClientBranchAction::run($this->client);
            $this->service_collection = GetServicesAction::run($this->branch_ids, $this->gender);
        }

    }

    #[On('card-taksit-client-selected')]
    public function dispatchClientSelected($client)
    {
        $this->client = $client;
        $this->client_model = null;
        $this->init();
    }

    public function deleteItem($id)
    {
        $this->selected_taksits = $this->selected_taksits->reject(function ($q) use ($id) {
            return $q['id'] == $id;
        });
        $this->dispatchSelectedTaksits();
    }

    public function deleteTaksits()
    {
        $this->selected_taksits = collect();
        $this->dispatchSelectedTaksits();
    }

    public function dispatchSelectedTaksits()
    {
        $this->dispatch('card-taksit-selected-taksits-updated', $this->selected_taksits);
    }

    #[On('card-taksit-added')]
    public function addTaksit($info)
    {
        for ($i = 0; $i < $info['amount']; $i++) {
            $lastId = $this->selected_taksits->last() != null ? $this->selected_taksits->last()['id'] + 1 : 1;
            $this->selected_taksits->push([
                'id' => $lastId,
                'date' => Carbon::parse($info['first_date'])->addMonth($i)->format('d/m/Y'),
                'price' => $info['price'],
                'locked' => [],
            ]);
        }

        $this->dispatchSelectedTaksits();
    }

    #[On('taksit-locked-service-added')]
    public function addTaksitService($info)
    {
        $this->selected_taksits->transform(function ($item) use ($info) {
            if ($item['id'] == $info['taksit_id']) {
                $item['locked'][] = [
                    'service_ids' => $info['service_ids'],
                    'quantity' => $info['quantity'],
                ];
            }

            return $item;
        });
    }

    public function showProductModal($taksit_id)
    {
        $this->dispatch('card-taksit-add-locked-clicked', $taksit_id)->to('components.card.taksit.card_taksit_add_locked_service');
    }
};

?>

<div>
    <x-card title="Taksitler" separator progress-indicator>
        @foreach ($selected_taksits as $taksit)
        <x-list-item :item="$taksit" no-separator no-hover>
            <x-slot:avatar>
                <x-badge :value="$taksit['id']" class="badge-primary text-l indicator-item" />
            </x-slot:avatar>
            <x-slot:value>
                {{ $taksit['date'] }}
            </x-slot:value>
            <x-slot:sub-value>
                {{ LiveHelper::price_text($taksit['price']) }}
            </x-slot:sub-value>
            <x-slot:actions>
                <x-button icon="o-lock-closed" wire:click="showProductModal({{ $taksit['id'] }})" class="text-blue-500" spinner
                tooltip="Bu taksit ödendiğinde hangi hizmetler açılacak?">
                <x-badge :value="count($taksit['locked'])" class="badge-primary indicator-item" />
                </x-button>
                <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                    wire:click="deleteItem({{ $taksit['id'] }})" spinner="deleteItem" />
            </x-slot:actions>
        </x-list-item>
        @endforeach
        <x:slot:menu>
            <livewire:components.card.taksit.card_taksit_add_taksit />
            @if ($selected_taksits->count() > 0)
            <x-button icon="o-trash" class="text-red-500 btn-sm" wire:confirm="Emin misiniz ?"
                wire:click="deleteTaksits()" spinner />
            @endif
        </x:slot:menu>
    </x-card>
    <livewire:components.card.taksit.card_taksit_add_locked_service :service_collection="$service_collection" :show="$show_product_modal" />
</div>