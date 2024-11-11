<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast, \Livewire\WithPagination, \App\Traits\WithViewPlaceHolder, \Livewire\WithoutUrlPagination;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    public function mount(): void
    {
        $this->sortBy = ['column' => 'status', 'direction' => 'asc'];
    }

    public function getData()
    {
        return \App\Actions\Client\GetClientAppointments::run($this->client, true, $this->sortBy);
    }

    public function headers(): array
    {
        return [
            ['key' => 'serviceRoom.name', 'label' => 'Oda', 'sortBy' => 'service_room_id'],
            ['key' => 'serviceCategory.name', 'label' => 'Kategori', 'sortBy' => 'service_category_id'],
            ['key' => 'services', 'label' => 'Hizmetler', 'sortable' => false],
            ['key' => 'duration', 'label' => 'Süre', 'sortBy' => 'duration'],
            ['key' => 'date', 'label' => 'Tarih', 'sortBy' => 'date'],
            ['key' => 'date_start', 'label' => 'Başlangıç', 'sortBy' => 'date_start'],
            ['key' => 'date_end', 'label' => 'Bitiş', 'sortBy' => 'date_end'],
            ['key' => 'status', 'label' => 'Durum', 'sortBy' => 'status'],
        ];
    }

    public function with()
    {
        return [
            'appointments' => $this->getData(),
            'headers' => $this->headers()
        ];
    }

};

?>
