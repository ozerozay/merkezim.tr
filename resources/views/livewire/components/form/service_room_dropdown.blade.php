<?php

use Illuminate\Support\Collection;

new class extends \Livewire\Volt\Component {

    #[\Livewire\Attributes\Modelable]
    public ?int $service_room_id;

    public ?int $branch_id;

    public Collection $service_rooms;

    public function mount(): void
    {
        $this->getServiceRooms($this->branch_id);
    }

    #[\Livewire\Attributes\On('reload-branch-service-rooms')]
    public function getServiceRooms($branch_id): void
    {
        $this->service_room = null;
        $this->service_rooms = \App\Actions\ServiceRoom\GetServiceRoomsAction::run($branch_id, null);
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
