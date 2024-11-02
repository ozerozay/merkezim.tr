<?php

use Illuminate\Support\Collection;

new class extends \Livewire\Volt\Component {

    #[\Livewire\Attributes\Modelable]
    public ?int $service_room_id;

    public ?int $client_id;

    public ?int $category_id;

    public Collection $service_rooms;

    public function mount(): void
    {
        $this->getServiceRooms($this->client_id, $this->category_id);
    }

    #[\Livewire\Attributes\On('reload-client-service-rooms')]
    public function getServiceRooms($client, $category_id): void
    {
        $client_branch_id = \App\Actions\Client\GetClientBranch::run($client);
        $this->service_room = null;
        $this->service_rooms = ($client_branch_id == null ? [] : \App\Actions\ServiceRoom\GetServiceRoomsAction::run($client_branch_id, $category_id));
    }

};

?>
<div>
    <x-choices-offline
        wire:model="service_room_id"
        :options="$service_rooms"
        option-label="name"
        option-sub-label="branch.name"
        label="Hizmet Odası"
        icon="o-magnifying-glass"
        no-result-text="Aktif hizmet odası bulunmuyor."
        single
        searchable/>
</div>
