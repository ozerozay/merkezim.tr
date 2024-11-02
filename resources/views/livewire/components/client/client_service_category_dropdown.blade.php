<?php

use App\Actions\Client\GetClientActiveServiceCategory;

new class extends \Livewire\Volt\Component {

    #[\Livewire\Attributes\Modelable]
    public ?int $category_id;

    public ?int $client_id = null;

    public \Illuminate\Support\Collection $categories;

    public function mount(): void
    {
        $this->getServiceCategories($this->client_id);
    }

    #[\Livewire\Attributes\On('reload-client-service-categories')]
    public function reloadServiceCategories($client): void
    {
        $this->client_id = $client;
        $this->getServiceCategories($client);
    }

    public function getServiceCategories($client): void
    {
        $this->categories = GetClientActiveServiceCategory::run($client);
    }

};

?>
<div>
    <x-choices-offline
        wire:model="category_id"
        :options="$categories"
        option-label="name"
        label="Aktif Hizmet Kategorileri"
        icon="o-magnifying-glass"
        no-result-text="Aktif hizmet kategorisi bulunmuyor."
        single
        searchable/>
</div>

