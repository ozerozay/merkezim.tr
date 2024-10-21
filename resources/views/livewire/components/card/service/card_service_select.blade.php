<?php

use App\Actions\Client\GetClientByUniqueID;
use App\Actions\Package\GetPackagesAction;
use App\Actions\Service\GetServicesAction;
use App\Actions\User\UserBranchOrClientBranchAction;
use App\Models\Package;
use App\Models\Service;
use App\Traits\LiveHelper;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public ?Collection $selected_services;

    public $branch_ids = [];

    public $client = null;

    public $client_model = null;

    public $gender = 0;

    public $service_collection;

    public $package_collection;

    public function mount()
    {
        LiveHelper::class;

        $this->init();

    }

    public function init()
    {
        $this->client_model = GetClientByUniqueID::run(null, $this->client);
        $this->gender = $this->client_model?->gender == 1 ? 1 : 2;
        $this->branch_ids = UserBranchOrClientBranchAction::run($this->client);

        $this->updateServices();
        $this->updatePackages();
    }

    public function updateServices()
    {
        $this->service_collection = GetServicesAction::run($this->branch_ids, $this->gender);
        $this->dispatch('card-service-add-service-update-collection', $this->service_collection)->to('components.card.service.card_service_add_service');
    }

    public function updatePackages()
    {
        $this->package_collection = GetPackagesAction::run($this->branch_ids, $this->gender);
        $this->dispatch('card-service-add-package-update-collection', $this->package_collection)->to('components.card.service.card_service_add_package');
    }

    #[Computed]
    public function totalPrice()
    {
        $totalP = 0.0;
        foreach ($this->selected_services as $s) {
            $totalP += $s['price'] * $s['quantity'];
        }

        return $totalP;
    }

    #[On('card-service-added')]
    public function addService($service)
    {
        $services = Service::whereIn('id', $service['service_id'])->get();

        foreach ($services as $s) {
            if ($this->selected_services->count() > 0 && ($service['gift'] != true) && $this->selected_services->contains(function ($q) use ($s) {
                return $q['type'] == 'service' && $q['service_id'] == $s->id;
            })) {
                $this->error($s->name.' bulunuyor, tablodan değişiklik yapın.');
                break;
            }

            $lastId = $this->selected_services->last() != null ? $this->selected_services->last()['id'] + 1 : 1;
            $this->selected_services->push([
                'id' => $lastId,
                'service_id' => $s->id,
                'type' => 'service',
                'name' => $s->name,
                'gift' => $service['gift'],
                'quantity' => $service['quantity'],
                'price' => $s->price,
            ]);
        }

        $this->dispatchSelectedServices();
    }

    #[On('card-package-added')]
    public function addPackage($info)
    {
        $package = Package::where('id', $info['package_id'])->first();

        if ($package) {
            if ($this->selected_services->count() > 0 && ($info['gift'] != true) && $this->selected_services->contains(function ($q) use ($package) {
                return $q['type'] == 'package' && $q['id'] == $package->id;
            })) {
                $this->error($package->name.' bulunuyor, değişikliği tablodan yapın.');

                return;
            }
            $this->selected_services->push([
                'id' => $package->id,
                'type' => 'package',
                'gift' => $info['gift'],
                'name' => $package->name,
                'quantity' => $info['quantity'],
                'price' => $package->price,
            ]);
        }

        $this->dispatchSelectedServices();
    }

    public function deleteItem($id, $type)
    {
        $this->selected_services = $this->selected_services->reject(function ($item) use ($id, $type) {
            return $item['id'] == $id && $item['type'] == $type;
        });
        $this->dispatchSelectedServices();
    }

    public function deleteServices()
    {
        $this->selected_services = collect();
        $this->dispatchSelectedServices();
    }

    public function dispatchSelectedServices()
    {
        $this->dispatch('card-service-selected-services-updated', $this->selected_services);
    }

    #[On('card-service-client-selected')]
    public function dispatchClientSelected($client)
    {
        $this->client = $client;
        $this->client_model = null;
        $this->gender = null;
        $this->init();
    }
};

?>
<div>
    <x-card title=" " separator progress-indicator>
        @foreach ($selected_services as $service)
        <x-list-item :item="$service" no-separator no-hover>
            @if ($service['gift'])
            <x-slot:avatar>
                <x-badge value="H" class="badge-primary" />
            </x-slot:avatar>
            @endif
            <x-slot:value>
                {{ $service['name'] }}
            </x-slot:value>
            <x-slot:sub-value>
                {{ $service['quantity'] }} seans - {{ LiveHelper::price_text($service['price']) }}
            </x-slot:sub-value>
            <x-slot:actions>
                <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                    wire:click="deleteItem({{ $service['id'] }}, 'service')" spinner />
            </x-slot:actions>
        </x-list-item>
        @endforeach
        <x:slot:menu>
            <livewire:components.card.service.card_service_add_service :service_collection="$service_collection" />
            <livewire:components.card.service.card_service_add_package :package_collection="$package_collection" />
            @if ($selected_services->count() > 0)
            <x-button icon="o-trash" class="text-red-500 btn-sm" wire:confirm="Emin misiniz ?"
                wire:click="deleteServices({{ $service['id'] }}, 'service')" spinner />
            @endif
        </x:slot:menu>
        <x:slot:actions>
        Toplam : {{ LiveHelper::price_text($this->totalPrice()) }}
        </x:slot:actions>
    </x-card>
</div>