<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    public array $date_config = [
        'altFormat' => 'd/m/Y',
        'dateFormat' => 'Y-m-d',
        'locale' => 'tr',
        'enableTime' => false
    ];

    #[\Livewire\Attributes\Url]
    public ?string $date;

    #[\Livewire\Attributes\Url]
    public ?int $branch;

    public ?array $statutes = [];

    #[\Livewire\Attributes\Url(as: 'status')]
    public ?string $statutes_url;

    public array $sortBy = [
        ['key' => 'time_asc', 'name' => 'Saat (Artan)', 'column' => 'date_start', 'direction' => 'asc', 'icon' => 'tabler.sort-ascending'],
        ['key' => 'time_desc', 'name' => 'Saat (Azalan)', 'column' => 'date_start', 'direction' => 'desc', 'icon' => 'tabler.sort-descending'],
        ['key' => 'duration_asc', 'name' => 'Süre (Artan)', 'column' => 'duration', 'direction' => 'asc', 'icon' => 'tabler.sort-ascending'],
        ['key' => 'duration_desc', 'name' => 'Saat (Azalan)', 'column' => 'duration', 'direction' => 'desc', 'icon' => 'tabler.sort-descending'],
        ['key' => 'status', 'name' => 'Durum', 'column' => 'status', 'direction' => 'asc', 'icon' => 'tabler.sort-ascending']
    ];

    #[\Livewire\Attributes\Url(as: 'sort')]
    public ?string $sortKey;

    public function mount(): void
    {
        $this->date = \Carbon\Carbon::now()->format('Y-m-d');
        try {
            if (isset($this->statutes_url)) {
                $this->statutes = collect(explode(',', $this->statutes_url))
                    ->mapWithKeys(function ($status) {
                        return \App\AppointmentStatus::has($status) ? [$status => true] : [];
                    })->toArray();
            }
        } catch (\Throwable $e) {
        }
    }

    public function filterBranch($id): void
    {
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

            $this->statutes_url = $trueStatutes->isNotEmpty()
                ? implode(',', $trueStatutes->keys()->toArray())
                : null;

        } catch (\Throwable $e) {
        }
    }

    public function with(): array
    {
        return [
            'sortBy' => $this->sortBy
        ];
    }
};

?>
<div>
    <x-header title="{{ \Carbon\Carbon::parse($this->date)->format('d/m/Y')  }}" separator progress-indicator>
        <x-slot:actions>
            <x-datepicker wire:model.live="date" :config="$date_config" icon="o-calendar"/>
            <x-dropdown label="Şube" icon="tabler.building-store">
                @foreach(auth()->user()->staff_branch as $branch)
                    <x-menu-item @click="$wire.filterBranch({{$branch->id}})" title="{{$branch->name}}"/>
                @endforeach
                <x-slot:trigger>
                    <x-button icon="tabler.building-store" class="btn-outline" label="Şube" responsive/>
                </x-slot:trigger>
            </x-dropdown>
            <x-dropdown label="Settings" class="btn-outline">
                @foreach(\App\AppointmentStatus::cases() as $status)
                    <x-menu-item @click.stop="">
                        <x-checkbox wire:model="statutes.{{$status->name}}" label="{{$status->label()}}"/>
                    </x-menu-item>
                @endforeach
                <x-menu-item>
                    <x-button icon="tabler.filter" class="btn-outline btn-sm" label="Filtrele"
                              wire:click="filterStatus"/>
                </x-menu-item>
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Filtrele" responsive/>
                </x-slot:trigger>
            </x-dropdown>
            <x-dropdown label="Şube" icon="tabler.building-store">
                @foreach($sortBy as $sortItem)
                    <x-menu-item title="{{ $sortItem['name'] }}"
                                 wire:click="filterSort('{{ $sortItem['key']  }}')"
                                 icon="{{ $sortItem['icon']  }}"/>
                @endforeach
                <x-menu-separator/>
                <x-menu-item title="Sıfırla" wire:click="filterSort(false)" icon="tabler.filter-off"/>
                <x-slot:trigger>
                    <x-button icon="tabler.sort-descending" class="btn-outline" label="Sırala" responsive/>
                </x-slot:trigger>
            </x-dropdown>
            <x-button icon="o-plus" class="btn-primary" label="Randevu Oluştur" responsive/>
        </x-slot:actions>
    </x-header>
    <x-card title="EPİLASYON 1" separator>
        <x-slot:menu>
            <x-badge class="badge-primary" value="Çalışma Süresi: 9 Saat"/>
            <x-badge class="badge-primary" value="Çalışma Süresi: 4 Saat"/>
        </x-slot:menu>
        <div class="flex w-full h-8 text-white text-center">
            <!-- 1 saat dolu, 12:00-13:00 -->
            <div class="bg-blue-500 h-full flex items-center justify-center" style="width: 11.11%;">
                12:00-13:00
            </div>
            <!-- 3 saat boş, 13:00-16:00 -->
            <div class="bg-gray-200 text-gray-800 h-full flex items-center justify-center" style="width: 33.33%;">
                13:00-16:00
            </div>
            <!-- 2 saat dolu, 16:00-18:00 -->
            <div class="bg-blue-500 h-full flex items-center justify-center" style="width: 22.22%;">
                16:00-18:00
            </div>
            <!-- 3 saat boş, 18:00-21:00 -->
            <div class="bg-gray-200 text-gray-800 h-full flex items-center justify-center" style="width: 33.33%;">
                18:00-21:00
            </div>
        </div>
    </x-card>
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5 mb-5">
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SÜRE
                </x-slot:value>
                <x-slot:actions>
                    45 DK
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    GECİKMİŞ ÖDEME
                </x-slot:value>
                <x-slot:actions>
                    @price(1500)
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            <p class="mt-3">TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)</p>
            <p class="mt-3">"RANDEVU NOTU NUTSADFLASK FLASDKF LJASDŞKF ŞLDKN FŞDSAFN LKSDFNLSD"</p>
        </x-card>
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    GECİKMİŞ ÖDEME
                </x-slot:value>
                <x-slot:actions>
                    @price(1500)
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            <p class="mt-3">TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)</p>
        </x-card>
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    GECİKMİŞ ÖDEME
                </x-slot:value>
                <x-slot:actions>
                    @price(1500)
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            <p class="mt-3">TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)</p>
        </x-card>
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    GECİKMİŞ ÖDEME
                </x-slot:value>
                <x-slot:actions>
                    @price(1500)
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            <p class="mt-3">TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)</p>
        </x-card>
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    GECİKMİŞ ÖDEME
                </x-slot:value>
                <x-slot:actions>
                    @price(1500)
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            <p class="mt-3">TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)</p>
        </x-card>
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)
        </x-card>
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)
        </x-card>
        <x-card separator>
            <x-slot:title>
                <div class="flex flex-auto items-center">
                    <x-badge class="badge-primary"/>
                    <p class="ml-2">CİHAT ÖZER ÖZAY</p>
                </div>
            </x-slot:title>
            <x-slot:menu>
                <x-button icon="tabler.settings" class="btn-outline btn-sm" responsive/>
            </x-slot:menu>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    SAAT
                </x-slot:value>
                <x-slot:actions>
                    14:20 - 15:40
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    DURUM
                </x-slot:value>
                <x-slot:actions>
                    <x-badge class="badge-primary" value="BEKLENİYOR"/>
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$sortBy">
                <x-slot:value>
                    TELEFON
                </x-slot:value>
                <x-slot:actions>
                    5056277636
                </x-slot:actions>
            </x-list-item>
            TÜM BACAK(1) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2) - KOLTUK ALTI(2)
        </x-card>
    </div>
    <x-card title="EPİLASYON 2" separator/>
</div>

