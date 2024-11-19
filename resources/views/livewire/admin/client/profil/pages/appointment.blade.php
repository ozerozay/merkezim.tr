<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast, \Livewire\WithPagination, \App\Traits\WithViewPlaceHolder, \Livewire\WithoutUrlPagination;

    public ?int $client;

    public ?bool $editing = false;

    public ?int $selectedAppointment = null;

    protected $listeners = [
        'refresh-appointments' => '$refresh'
    ];

    #[\Livewire\Attributes\On('appointment-show-drawer')]
    public function showSettings($id): void
    {
        $this->dispatch('drawer-appointment-update-id', $id)->to('components.drawers.drawer_appointment');
        $this->editing = true;
    }

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
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
                  icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline"/>
    </div>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($appointments as $appointment)
                <livewire:components.card.appointment.card_appointment_client
                    wire:key="{{ $appointment->id }}"
                    :appointment="$appointment"/>
            @endforeach
        </div>
    @endif
    <livewire:components.drawers.drawer_appointment wire:model="editing"/>
</div>

