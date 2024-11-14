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
            ['key' => 'serviceNames', 'label' => 'Hizmetler', 'sortable' => false],
            ['key' => 'duration', 'label' => 'Süre', 'sortBy' => 'duration'],
            ['key' => 'dateHuman', 'label' => 'Tarih', 'sortBy' => 'date'],
            ['key' => 'dateHumanStart', 'label' => 'Başlangıç', 'sortBy' => 'date_start'],
            ['key' => 'dateHumanEnd', 'label' => 'Bitiş', 'sortBy' => 'date_end'],
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
<div>
    @if ($view)
        <x-card title="">
            <x-table :headers="$headers" :rows="$appointments" :sort-by="$sortBy" striped
                     with-pagination>
                <x-slot:empty>
                    <x-icon name="o-cube" label="Randevu bulunmuyor."/>
                </x-slot:empty>
                @can('appointment_process')
                    @scope('actions', $appointment)
                    <x-button icon="tabler.settings"
                              wire:click="showSettings({{ $appointment->id }})"
                              class="btn-circle btn-sm btn-primary"/>
                    @endscope
                @endcan
                @scope('cell_status', $appointment)
                <x-badge :value="$appointment->status->label()" class="badge-{{ $appointment->status->color() }}"/>
                @endscope
                @scope('cell_duration', $appointment)
                {{ $appointment->duration  }} dk
                @endscope
            </x-table>
        </x-card>
    @else
    @endif
</div>

