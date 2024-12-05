<?php

new #[\Livewire\Attributes\Title('Randevu')] #[Lazy] class extends \Livewire\Volt\Component
{
    use \Mary\Traits\Toast;

    public array $date_config = [];

    #[\Livewire\Attributes\Url]
    public ?string $date = null;

    #[\Livewire\Attributes\Url]
    public ?int $branch = null;

    public ?string $branchName = null;

    public ?array $statutes = [];

    #[\Livewire\Attributes\Url(as: 'status')]
    public ?string $statutes_url = null;

    public ?bool $editing = false;

    public ?int $selectedAppointment = null;

    protected $listeners = [
        'refresh-appointments' => '$refresh',
    ];

    public array $sortBy = [['key' => 'time_asc', 'name' => 'Saat (Artan)', 'column' => 'date_start', 'direction' => 'asc', 'icon' => 'tabler.sort-ascending'], ['key' => 'time_desc', 'name' => 'Saat (Azalan)', 'column' => 'date_start', 'direction' => 'desc', 'icon' => 'tabler.sort-descending'], ['key' => 'duration_asc', 'name' => 'Süre (Artan)', 'column' => 'duration', 'direction' => 'asc', 'icon' => 'tabler.sort-ascending'], ['key' => 'duration_desc', 'name' => 'Saat (Azalan)', 'column' => 'duration', 'direction' => 'desc', 'icon' => 'tabler.sort-descending'], ['key' => 'status', 'name' => 'Durum', 'column' => 'status', 'direction' => 'asc', 'icon' => 'tabler.sort-ascending']];

    #[\Livewire\Attributes\Url(as: 'sort')]
    public ?string $sortKey;

    #[\Livewire\Attributes\On('appointment-show-drawer')]
    public function showSettings($id): void
    {
        $this->dispatch('drawer-appointment-update-id', $id)->to('components.drawers.drawer_appointment');
        $this->editing = true;
    }

    public function mount(): void
    {
        $this->date_config = \App\Peren::dateConfig(enableTime: false);
        if (! $this->date) {
            $this->date = date('Y-m-d');
        }

        try {
            if (isset($this->statutes_url)) {
                $this->statutes = collect(explode(',', $this->statutes_url))
                    ->mapWithKeys(function ($status) {
                        return \App\AppointmentStatus::has($status) ? [$status => true] : [];
                    })
                    ->toArray();
            }
        } catch (\Throwable $e) {
        }
    }

    public function filterBranch($id): void
    {
        $this->branchName = \App\Models\Branch::where('id', $id)->pluck('name')?->first() ?? null;
        $this->branch = $id;
    }

    public function filterSort($key): void
    {
        if (! $key) {
            $this->sortKey = null;

            return;
        }
        $this->sortKey = $key;
    }

    public function filterStatus(): void
    {
        try {
            if (! in_array(true, $this->statutes, true)) {
                $this->statutes_url = null;

                return;
            }

            $trueStatutes = collect($this->statutes)->filter(function ($q) {
                return $q == true;
            });

            $this->statutes_url = $trueStatutes->isNotEmpty() ? implode(',', $trueStatutes->keys()->toArray()) : null;
        } catch (\Throwable $e) {
        }
    }

    public function getAppointments(): \Illuminate\Database\Eloquent\Collection
    {
        $this->branchName = $this->branchName ?? auth()->user()->staff_branch()->first()->name;

        return App\Models\ServiceRoom::query()
            ->where('active', true)
            ->when($this->branch, function ($q) {
                $q->where('branch_id', $this->branch);
            })
            ->when(! $this->branch, function ($q) {
                $q->where('branch_id', auth()->user()->staff_branch()->first()->id);
            })
            ->with('appointments', function ($q) {
                $q->when($this->date, function ($qd) {
                    $qd->where('date', $this->date);
                })
                    ->when(! $this->date, function ($dtd) {
                        $dtd->where('date', date('Y-m-d'));
                    })
                    ->when($this->statutes_url, function ($qs) {
                        $qs->whereIn('status', explode(',', $this->statutes_url));
                    })
                    ->orderBy('date_start', 'asc')
                    ->with('client:id,name,phone', 'serviceRoom:id,name', 'services.service');
            })
            ->orderBy('name', 'asc')
            ->get();
        //dump($rooms);

        /*return \App\Models\Appointment::query()
            ->where(function ($q) {
                if ($this->branch) {
                    $q->where('branch_id', $this->branch);
                } else {
                    $q->where('branch_id', auth()->user()->staff_branch()->first()->id);
                }
            })
            ->where(function ($q) {
                if ($this->date) {
                    $q->where('date', $this->date);
                } else {
                    $q->where('date', date('Y-m-d'));
                }
            })
            ->where(function ($q) {
                if ($this->statutes_url) {
                    $q->whereIn('status', explode(',', $this->statutes_url));
                }
            })
            //->leftJoin('service_rooms', 'appointments.branch_id', '=', 'service_rooms.branch_id')
            ->with('client:id,name,phone', 'serviceRoom:id,name', 'services.service')
            ->get()
            ->groupBy('service_room_id');*/
    }

    public function with(): array
    {
        //dump($this->getAppointments());
        return [
            'sortBy' => $this->sortBy,
            'appointments_group' => $this->getAppointments(),
        ];
    }
};

?>
<div>
    <x-header title="{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}" subtitle="{{ $branchName ?? '' }}"
        separator progress-indicator>
        <x-slot:actions>
            <x-datepicker wire:model.live="date" :config="$date_config" icon="o-calendar" />
            <x-dropdown label="Şube" icon="tabler.building-store">
                @foreach (auth()->user()->staff_branch as $branch)
                    <x-menu-item @click="$wire.filterBranch({{ $branch->id }})" title="{{ $branch->name }}" />
                @endforeach
                <x-slot:trigger>
                    <x-button icon="tabler.building-store" class="btn-outline" label="{{ $branchName ?? 'Şube' }}"
                        responsive />
                </x-slot:trigger>
            </x-dropdown>
            <x-dropdown label="Settings" class="btn-outline">
                @foreach (\App\AppointmentStatus::cases() as $status)
                    <x-menu-item @click.stop="">
                        <x-checkbox wire:model="statutes.{{ $status->name }}" label="{{ $status->label() }}" />
                    </x-menu-item>
                @endforeach
                <x-menu-item>
                    <x-button icon="tabler.filter" class="btn-outline btn-sm" label="Filtrele"
                        wire:click="filterStatus" />
                </x-menu-item>
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Filtrele" responsive />
                </x-slot:trigger>
            </x-dropdown>
            @if (1 == 2)
                <x-dropdown label="Şube" icon="tabler.building-store">
                    @foreach ($sortBy as $sortItem)
                        <x-menu-item title="{{ $sortItem['name'] }}" wire:click="filterSort('{{ $sortItem['key'] }}')"
                            icon="{{ $sortItem['icon'] }}" />
                    @endforeach
                    <x-menu-separator />
                    <x-menu-item title="Sıfırla" wire:click="filterSort(false)" icon="tabler.filter-off" />
                    <x-slot:trigger>
                        <x-button icon="tabler.sort-descending" class="btn-outline" label="Sırala" responsive />
                    </x-slot:trigger>
                </x-dropdown>
            @endif
            @if (1 == 2)
                @can('action_client_create_appointment')
                    <x-button icon="o-plus" link="{{ route('admin.actions.client_create_appointment') }}"
                        class="btn-primary" label="Randevu Oluştur" responsive />
                @endcan
            @endif
        </x-slot:actions>
    </x-header>
    @if (1 == 2)
        @if ($appointments_group->isEmpty())
            <livewire:components.card.loading.empty />
        @endif
        @foreach ($appointments_group as $appointments)
            <x-card title="{{ $appointments->first()->serviceRoom->name }}" separator>
                <x-slot:menu>
                    <x-badge class="badge-primary" value="Çalışma Süresi: 9 Saat" />
                    <x-badge class="badge-primary" value="Çalışma Süresi: 4 Saat" />
                </x-slot:menu>
                <div class="flex w-full h-8 text-white text-center">
                    <!-- 1 saat dolu, 12:00-13:00 -->
                    <div class="bg-blue-500 h-full flex items-center justify-center" style="width: 11.11%;">
                        12:00-13:00
                    </div>
                    <!-- 3 saat boş, 13:00-16:00 -->
                    <div class="bg-gray-200 text-gray-800 h-full flex items-center justify-center"
                        style="width: 33.33%;">
                        13:00-16:00
                    </div>
                    <!-- 2 saat dolu, 16:00-18:00 -->
                    <div class="bg-blue-500 h-full flex items-center justify-center" style="width: 22.22%;">
                        16:00-18:00
                    </div>
                    <!-- 3 saat boş, 18:00-21:00 -->
                    <div class="bg-gray-200 text-gray-800 h-full flex items-center justify-center"
                        style="width: 33.33%;">
                        18:00-21:00
                    </div>
                </div>
            </x-card>
            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5 mb-5">
                @foreach ($appointments as $appointment)
                    @if ($appointment->type == \App\AppointmentType::appointment)
                        <livewire:components.card.appointment.card_appointment_client wire:key="{{ $appointment->id }}"
                            :appointment="$appointment" />
                    @elseif($appointment->type == \App\AppointmentType::close)
                        <livewire:components.card.appointment.card_appointment_close wire:key="{{ $appointment->id }}"
                            :appointment="$appointment" />
                    @endif
                @endforeach
            </div>
        @endforeach
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($appointments_group as $room)
            <x-card separator title="{{ $room->name }}">
                <x:slot:menu>
                    Kalan:
                    {{ $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->count() }} -
                    {{ $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->sum('duration') }}
                    DK
                </x:slot:menu>
                @if ($room->appointments->isEmpty())
                    <p class="text-center">Randevu bulunmuyor.</p>
                @endif
                @php
                    $active_appointments = $room->appointments
                        ->whereIn('status', \App\AppointmentStatus::active()->all())
                        ->all();
                    $other_appointments = $room->appointments
                        ->whereNotIn('status', \App\AppointmentStatus::active()->all())
                        ->all();
                @endphp
                @foreach ($active_appointments as $appointment)
                    <x-list-item :item="$appointment" class="cursor-pointer"
                        wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})">
                        <x-slot:avatar>
                            <x-badge value="{{ $appointment->date_start->format('H:i') }}"
                                class="badge-{{ $appointment->status->color() }}" />
                            <br />
                            <x-badge value="{{ $appointment->date_end->format('H:i') }}"
                                class="badge-{{ $appointment->status->color() }}" />
                            <br />
                        </x-slot:avatar>
                        <x-slot:value>
                            {{ $appointment->duration }} DK - {{ $appointment->client->name }}
                        </x-slot:value>
                        <x-slot:sub-value>
                            {{ $appointment->serviceNames }}
                        </x-slot:sub-value>
                        <x-slot:actions>
                            <x-badge value="{{ $appointment->status->label() }}"
                                class="badge-{{ $appointment->status->color() }}" />
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
                <x-hr />
                @foreach ($other_appointments as $appointment)
                    <x-list-item :item="$appointment" class="cursor-pointer"
                        wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})">
                        <x-slot:avatar>
                            <x-badge value="{{ $appointment->date_start->format('H:i') }}"
                                class="badge-{{ $appointment->status->color() }}" />
                            <br />
                            <x-badge value="{{ $appointment->date_end->format('H:i') }}"
                                class="badge-{{ $appointment->status->color() }}" />
                            <br />
                        </x-slot:avatar>
                        <x-slot:value>
                            {{ $appointment->duration }} DK - {{ $appointment->client->name }}
                        </x-slot:value>
                        <x-slot:sub-value>
                            {{ $appointment->serviceNames }}
                        </x-slot:sub-value>
                        <x-slot:actions>
                            <x-badge value="{{ $appointment->status->label() }}"
                                class="badge-{{ $appointment->status->color() }}" />
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </x-card>
        @endforeach
    </div>
</div>
