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
    <!-- Header -->
    <x-header title="Randevular" separator progress-indicator>
        <x-slot:middle>
            <div class="flex items-center gap-3">
                <x-datepicker wire:model.live="date" :config="$date_config" />

                <!-- Özel Şube Seçici -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="btn btn-outline gap-2">
                        <x-icon name="o-building-office-2" class="w-5 h-5" />
                        <span>{{ $branchName ?? 'Şube Seç' }}</span>
                    </button>
                    <div x-show="open" 
                         x-transition
                         class="absolute right-0 mt-2 w-56 bg-base-100 rounded-lg shadow-xl z-50">
                        <div class="p-2">
                            @foreach (auth()->user()->staff_branch as $branch)
                                <button wire:click="filterBranch({{ $branch->id }})"
                                        @click="open = false"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-base-200 rounded-lg">
                                    {{ $branch->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </x-slot:middle>

        <x-slot:actions>
            <!-- Özel Filtre Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="btn btn-outline gap-2">
                    <x-icon name="o-funnel" class="w-5 h-5" />
                    <span>Filtrele</span>
                </button>

                <div x-show="open" 
                     x-transition
                     class="absolute right-0 mt-2 w-72 bg-base-100 rounded-lg shadow-xl z-50">
                    <div class="p-4 space-y-4">
                        <!-- Durum Filtreleri -->
                        <div>
                            <div class="font-medium text-sm mb-2">Durumlar</div>
                            <div class="space-y-2">
                                @foreach (\App\AppointmentStatus::cases() as $status)
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-base-200 p-2 rounded-lg">
                                        <input type="checkbox" 
                                               class="checkbox checkbox-sm" 
                                               wire:model.defer="statutes.{{ $status->name }}" />
                                        <span class="text-sm">{{ $status->label() }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button wire:click="filterStatus" 
                                        @click="open = false"
                                        class="btn btn-primary btn-sm w-full">
                                    Durumları Uygula
                                </button>
                            </div>
                        </div>

                        <div class="divider my-2"></div>

                        <!-- Görünüm Seçenekleri -->
                        <div>
                            <div class="font-medium text-sm mb-2">Görünüm</div>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-base-200 p-2 rounded-lg">
                                    <input type="checkbox" 
                                           class="checkbox checkbox-sm" 
                                           wire:model.live="showOnlyActive" />
                                    <span class="text-sm">Sadece Aktifler</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-base-200 p-2 rounded-lg">
                                    <input type="checkbox" 
                                           class="checkbox checkbox-sm" 
                                           wire:model.live="showGaps" />
                                    <span class="text-sm">Boşlukları Göster</span>
                                </label>
                            </div>
                        </div>

                        <div class="divider my-2"></div>

                        <!-- Sıralama -->
                        @if (1==2)
                        <div>
                            <div class="font-medium text-sm mb-2">Sıralama</div>
                            <div class="space-y-1">
                                @foreach($sortBy as $sort)
                                    <button wire:click="filterSort('{{ $sort['key'] }}')"
                                            class="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-base-200 rounded-lg {{ $sortKey === $sort['key'] ? 'bg-primary/10' : '' }}">
                                        <x-icon :name="$sort['icon']" class="w-4 h-4" />
                                        <span>{{ $sort['name'] }}</span>
                                        @if($sortKey === $sort['key'])
                                            <x-icon name="o-check" class="w-4 h-4 ml-auto text-primary" />
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-slot:actions>
    </x-header>

    <!-- Ana İçerik -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($appointments_group as $room)
            <div class="card bg-base-100">
                <!-- Oda Başlığı -->
                <div class="card-header p-4 border-b border-base-200 sticky top-0 bg-base-100 z-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="avatar placeholder">
                                <div class="w-8 h-8 rounded-lg bg-primary/10">
                                    <span class="text-primary">🏠</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold">{{ $room->name }}</h3>
                                <p class="text-xs opacity-70">
                                    {{ $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->count() }} Aktif Randevu
                                </p>
                            </div>
                        </div>
                        <div class="badge badge-primary">
                            {{ $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->sum('duration') }} DK
                        </div>
                    </div>
                </div>

                <!-- Randevular -->
                <div class="card-body p-4 space-y-3">
                    @php
                        $filteredAppointments = $showOnlyActive
                            ? $room->appointments->whereIn('status', \App\AppointmentStatus::active()->all())->values()
                            : $room->appointments->whereIn('status', array_merge(\App\AppointmentStatus::active()->all(), [\App\AppointmentStatus::finish]))->sortBy('date_start')->values();
                        $cancelled = $room->appointments->where('status', \App\AppointmentStatus::cancel)->sortBy('date_start')->values();
                    @endphp

                    @forelse($filteredAppointments as $index => $appointment)
                        <!-- Randevu Kartı -->
                        <div class="card bg-base-200 hover:bg-base-300 transition-colors cursor-pointer"
                             wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})">
                            <div class="card-body p-3">
                                <!-- Üst Bilgiler -->
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="badge badge-{{ $appointment->status->color() }}">
                                            {{ $appointment->status->label() }}
                                        </span>
                                        <span class="text-sm font-medium">
                                            {{ $appointment->date_start->format('H:i') }} - {{ $appointment->date_end->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="text-sm opacity-70">{{ $appointment->duration }} DK</div>
                                </div>

                                <!-- Müşteri ve Hizmet -->
                                <div class="mt-2">
                                    <div class="flex items-center gap-2">
                                        <div class="avatar placeholder">
                                            <div class="w-6 h-6 rounded-full bg-neutral/10">
                                                <span class="text-neutral text-xs">👤</span>
                                            </div>
                                        </div>
                                        <span class="font-medium">{{ $appointment->client->name }}</span>
                                    </div>
                                    <div class="mt-1 text-sm opacity-70">
                                        {{ $appointment->serviceNames }}
                                    </div>
                                </div>

                                <!-- Alt Bilgiler -->
                                <div class="mt-2 flex items-center gap-4 text-xs">
                                    @if($appointment->hasDelayedPayment)
                                        <span class="text-error">Gecikmiş Ödeme</span>
                                    @endif
                                    @if($appointment->hasActiveOffer)
                                        <span class="text-primary">Aktif Teklif</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Boşluk Gösterimi -->
                        @if ($showGaps)
                            @php
                                $nextApt = $filteredAppointments[$index + 1] ?? null;
                                $gapMinutes = $nextApt ? $appointment->date_end->diffInMinutes($nextApt->date_start) : 0;
                            @endphp
                            @if ($gapMinutes > 0)
                                <div class="flex items-center gap-2 p-2 bg-warning/10 rounded-lg">
                                    <x-icon name="o-clock" class="w-4 h-4 text-warning" />
                                    <span class="text-sm">
                                        {{ $gapMinutes >= 60 
                                            ? floor($gapMinutes / 60) . ' Saat ' . ($gapMinutes % 60) . ' DK'
                                            : $gapMinutes . ' DK' 
                                        }} Boş
                                    </span>
                                </div>
                            @endif
                        @endif
                    @empty
                        <div class="text-center py-6">
                            <div class="avatar placeholder mb-3">
                                <div class="w-12 h-12 rounded-full bg-base-300">
                                    <span class="text-2xl">📅</span>
                                </div>
                            </div>
                            <p class="text-sm opacity-70">Randevu bulunmuyor</p>
                        </div>
                    @endforelse

                    <!-- İptal Edilmiş Randevular -->
                    @if(!$showOnlyActive && $cancelled->count())
                        <div class="divider text-xs opacity-50">İptal Edilenler</div>
                        @foreach($cancelled as $appointment)
                            <div class="p-2 bg-base-200 rounded-lg opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
                                 wire:click="$dispatch('slide-over.open', {component: 'modals.appointment.appointment-modal', arguments: {'appointment': {{ $appointment->id }}}})">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm">{{ $appointment->date_start->format('H:i') }}</span>
                                        <span class="text-sm font-medium">{{ $appointment->client->name }}</span>
                                    </div>
                                    <span class="badge badge-sm badge-error">İptal</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
