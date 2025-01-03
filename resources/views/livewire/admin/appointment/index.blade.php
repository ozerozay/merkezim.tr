<?php

new #[\Livewire\Attributes\Title('Randevu')] #[Lazy] class extends \Livewire\Volt\Component {
    use \Mary\Traits\Toast;

    public array $date_config = [];

    public bool $showOnlyActive = false;

    public bool $showGaps = false;

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
        if (!$this->date) {
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
        if (!$key) {
            $this->sortKey = null;

            return;
        }
        $this->sortKey = $key;
    }

    public function filterStatus(): void
    {
        try {
            if (!in_array(true, $this->statutes, true)) {
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
            ->when(!$this->branch, function ($q) {
                $q->where('branch_id', auth()->user()->staff_branch()->first()->id);
            })
            ->with('appointments', function ($q) {
                $q->when($this->date, function ($qd) {
                    $qd->where('date', $this->date);
                })
                    ->when(!$this->date, function ($dtd) {
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
    <x-header
        title=" {{ $branchName ?? '' }} - {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}"
        separator
        progress-indicator
    >
        <x-slot:middle>
            <x-datepicker wire:model.live="date" :config="$date_config" />
        </x-slot:middle>

        <x-slot:actions>
            <x-dropdown label="🏬 Şube Seç">
                @foreach (auth()->user()->staff_branch as $branch)
                    <x-menu-item
                        @click="$wire.filterBranch({{ $branch->id }})"
                        title="{{ $branch->name }}"
                    />
                @endforeach
                <x-slot:trigger>
                    <x-button class="btn-outline" label="🏬 {{ $branchName ?? 'Şube' }}" />
                </x-slot:trigger>
            </x-dropdown>

            <x-dropdown label="⚙️ Ayarlar" class="btn-outline">
                @foreach (\App\AppointmentStatus::cases() as $status)
                    <x-menu-item @click.stop="">
                        <x-checkbox
                            wire:model="statutes.{{ $status->name }}"
                            label="{{ $status->label() }}"
                        />
                    </x-menu-item>
                @endforeach
                <x-menu-item>
                    <x-button
                        class="btn-outline btn-sm"
                        label="🔍 Filtrele"
                        wire:click="filterStatus"
                    />
                </x-menu-item>
                <x-slot:trigger>
                    <x-button class="btn-outline" label="🔍 Filtrele" />
                </x-slot:trigger>
            </x-dropdown>
        </x-slot:actions>
    </x-header>
    <div>
        <div>
            <div class="flex items-center mb-4 space-x-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="checkbox checkbox-primary" wire:model.live="showOnlyActive">
                    <span class="ml-2 text-sm font-semibold text-base-content">Sadece Aktifleri Göster</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="checkbox checkbox-secondary" wire:model.live="showGaps">
                    <span class="ml-2 text-sm font-semibold text-base-content">Boşlukları Göster</span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                @foreach ($appointments_group as $room)
                    <div class="col-span-1">
                        {{-- Oda Adı ve Kalanlar (Başlık Kartı) --}}
                        <div
                            class="flex items-center justify-between bg-base-100 shadow-md p-3 rounded mb-4 sticky top-0 z-10">
                            <span class="text-lg font-bold text-primary">💆‍♀️ {{ $room->name }}</span>
                            <p class="text-sm text-secondary font-semibold">
                                ⏳ {{ $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->count() }}
                                Kalan -
                                {{ $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->sum('duration') }}
                                DK
                            </p>
                        </div>


                        {{-- Kart (Randevuları İçeriyor) --}}
                        <div class="flex flex-col space-y-4">
                            @php
                                $filteredAppointments = $showOnlyActive
                                    ? $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->values()
                                    : $room->appointments
                                        ->whereIn('status', array_merge(\App\AppointmentStatus::active()->all(), [
                                            \App\AppointmentStatus::finish,
                                        ]))
                                        ->sortBy('date_start')
                                        ->values();

                                $cancelled = $room->appointments
                                    ->where('status', \App\AppointmentStatus::cancel)
                                    ->sortBy('date_start')
                                    ->values();
                            @endphp

                            @if ($filteredAppointments->isEmpty())
                                <p class="text-center text-neutral-content">
                                    📭 Randevu bulunmuyor.
                                </p>
                            @else
                                {{-- Filtrelenmiş Randevular --}}
                                @foreach ($filteredAppointments as $index => $appointment)
                                    @php
                                        $isCompleted = ($appointment->status === \App\AppointmentStatus::finish);
                                    @endphp

                                    {{-- Biten Randevu --}}
                                    @if ($isCompleted)
                                        <div
                                            class="flex justify-between items-center text-sm font-semibold text-base-content border-l-4 border-success p-2 rounded cursor-pointer"
                                            wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})">
                                            <div>
                                                ⏰ {{ $appointment->date_start->format('H:i') }}
                                                - {{ $appointment->date_end->format('H:i') }}
                                                • {{ $appointment->client->name }}
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span>{{ $appointment->finish_user->name }}</span>
                                                <span
                                                    class="badge badge-{{ $appointment->status->color() }}">{{ $appointment->status->label() }}</span>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Aktif Randevu (Kart) --}}
                                        <div
                                            class="border-l-4 border-primary p-5 bg-base-100 rounded-lg shadow-lg cursor-pointer"
                                            wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})"
                                        >
                                            <!-- Başlık -->
                                            <div class="flex justify-between items-center mb-4">
                                                <div>
                                                    <p class="text-lg font-bold text-primary">
                                                        ⏰ {{ $appointment->date_start->format('H:i') }}
                                                        - {{ $appointment->date_end->format('H:i') }}</p>
                                                </div>
                                                <span class="badge badge-{{ $appointment->status->color() }} text-sm">
            {{ $appointment->status->label() }}
        </span>
                                            </div>

                                            <!-- Müşteri ve Hizmet Bilgisi -->
                                            <div class="mb-4">
                                                <p class="text-base font-semibold text-base-content">
                                                    👤 {{ $appointment->client->name }}</p>
                                                <p class="text-sm text-neutral-content">✨
                                                    Hizmet: {{ $appointment->serviceNames }}</p>
                                            </div>

                                            <!-- Süre, Gecikmiş Ödeme, ve Aktif Teklif -->
                                            <div class="flex text-sm text-center">
                                                <div class="py-2 px-4">
                                                    <span
                                                        class="block">🕒 Süre: {{ $appointment->duration }} DK</span>
                                                </div>
                                                <div class="py-2 px-4">
                                                    <span
                                                        class="block">💰 Gecikmiş: {{ $appointment->hasDelayedPayment ? 'Evet' : 'Hayır' }}</span>
                                                </div>
                                                <div class="py-2 px-4">
                                                    <span
                                                        class="block">📜 Teklif: {{ $appointment->hasActiveOffer ? 'Evet' : 'Hayır' }}</span>
                                                </div>

                                            </div>


                                        </div>

                                    @endif

                                    {{-- GAP (Randevular Arasında) --}}
                                    @if ($showGaps)
                                        @php
                                            $nextApt = $filteredAppointments[$index + 1] ?? null;
                                            $gapMinutes = $nextApt
                                                ? $appointment->date_end->diffInMinutes($nextApt->date_start)
                                                : 0;
                                        @endphp
                                        @if ($gapMinutes > 0)
                                            @php
                                                $gap_hours = intdiv($gapMinutes, 60);
                                                $gap_mins  = $gapMinutes % 60;
                                                $gap_display = $gap_hours > 0
                                                    ? "{$gap_hours} Saat {$gap_mins} DK"
                                                    : "{$gapMinutes} DK";
                                            @endphp
                                            <div
                                                class="flex justify-between items-center text-sm font-semibold text-base-content border-l-4 border-warning p-2 rounded cursor-pointer">
                                                <div>
                                                    🕒 Boşluk: {{ $gap_display }}
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            @endif

                            {{-- İptal Randevular --}}
                            @if (!$showOnlyActive && $cancelled->count())
                                <x-hr class="my-4" />
                                @foreach ($cancelled as $appointment)
                                    <div class="border-l-4 border-error p-3 bg-base-100 rounded-md mb-2 cursor-pointer"
                                         wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})">
                                        <div class="flex justify-between items-center">
                                            <div class="text-sm font-semibold text-base-content flex space-x-2">
                                        <span>
                                            ⏰
                                            {{ $appointment->date_start->format('H:i') }}
                                            -
                                            {{ $appointment->date_end->format('H:i') }}
                                        </span>
                                                <span>
                                            • {{ $appointment->client->name }}
                                        </span>
                                            </div>
                                            <span
                                                class="badge badge-{{ $appointment->status->color() }} badge-sm text-xs font-semibold"
                                            >
                                        {{ $appointment->status->label() }}
                                    </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
