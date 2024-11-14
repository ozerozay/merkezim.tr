<?php

new
#[\Livewire\Attributes\Title('Talep')]
class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast, \Livewire\WithPagination, \Livewire\Features\SupportPagination\WithoutUrlPagination;

    public ?array $statutes = [\App\TalepStatus::bekleniyor->name];

    #[\Livewire\Attributes\Url(as: 'status')]
    public ?string $statutes_url = null;

    #[\Livewire\Attributes\Url]
    public $branch = null;

    public ?bool $editing = false;

    public ?int $selectedTalep = null;

    protected $listeners = [
        'refresh-talepss' => '$refresh'
    ];

    public function mount(): void
    {
        if (isset($this->statutes_url)) {
            $this->statutes = collect(explode(',', $this->statutes_url))
                ->mapWithKeys(function ($status) {
                    return \App\TalepStatus::has($status) ? [$status => true] : [];
                })->toArray();
        }
    }

    #[\Livewire\Attributes\On('talep-show-drawer')]
    public function showSettings($id): void
    {
        $this->dispatch('drawer-talep-update-id', $id)->to('components.drawers.drawer_talep');
        $this->editing = true;
    }

    public function filterStatus(): void
    {
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
    }

    public function filterBranch($id): void
    {
        $this->branch = $id;
    }

    public function getTaleps()
    {
        $branch = $this->branch;
        $status_url = $this->statutes_url;
        return \App\Models\Talep::query()
            ->where(function ($q) use ($branch) {
                if ($branch) {
                    $q->where('branch_id', $branch);
                } else {
                    $q->where('branch_id', auth()->user()->staff_branch()->first()->id);
                }
            })
            ->where(function ($q) use ($status_url) {
                if ($status_url) {
                    $q->whereIn('status', explode(',', $status_url));
                }
            })
            ->where(function ($q) {
                if (!auth()->user()->hasRole('admin')) {
                    $q->where('user_id', auth()->user()->id);
                }
            })
            ->with('latestAction', 'branch:id,name')
            ->withCount('talepProcesses')
            ->paginate(10);
    }

    public function with(): array
    {
        return [
            'taleps' => $this->getTaleps()
        ];
    }
};

?>
<div>
    <x-header title="Talep" separator progress-indicator>
        <x-slot:actions>
            <x-dropdown label="Şube" icon="tabler.building-store">
                @foreach(auth()->user()->staff_branch as $branch)
                    <x-menu-item @click="$wire.filterBranch({{$branch->id}})" title="{{$branch->name}}"/>
                @endforeach
                <x-slot:trigger>
                    <x-button icon="tabler.building-store" class="btn-outline" label="Şube" responsive/>
                </x-slot:trigger>
            </x-dropdown>
            <x-dropdown label="Filtrele" class="btn-outline">
                @foreach(\App\TalepStatus::cases() as $status)
                    <x-menu-item @click.stop="">
                        <x-checkbox wire:model="statutes.{{$status->name}}" label="{{$status->label()}}"/>
                    </x-menu-item>
                @endforeach
                <x-menu-item>
                    <x-button icon="tabler.filter" class="btn-outline btn-sm" label="Filtrele"
                              wire:click="filterStatus"/>
                </x-menu-item>
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Durum" responsive/>
                </x-slot:trigger>
            </x-dropdown>
            <livewire:admin.talep.create/>
        </x-slot:actions>
    </x-header>
    @if ($taleps->isEmpty())
        <livewire:components.card.loading.empty/>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5 mb-5">
        @foreach($taleps as $t)
            <livewire:components.card.talep.talep_card
                wire:key="{{ rand(100000000,999999999) }}"
                :talep="$t"/>
        @endforeach

    </div>
    <x-pagination :rows="$taleps"/>
    <livewire:components.drawers.drawer_talep wire:model="editing"/>
</div>
