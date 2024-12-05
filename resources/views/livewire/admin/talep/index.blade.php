<?php

new #[\Livewire\Attributes\Title('Talep')] class extends \Livewire\Volt\Component
{
    use \Livewire\Features\SupportPagination\WithoutUrlPagination, \Livewire\WithPagination, \Mary\Traits\Toast;

    public ?array $statutes = [\App\TalepStatus::bekleniyor->name];

    #[\Livewire\Attributes\Url(as: 'status')]
    public ?string $statutes_url = null;

    #[\Livewire\Attributes\Url]
    public $branch = null;

    public ?string $branchName = null;

    protected $listeners = [
        'refresh-taleps' => '$refresh',
    ];

    public function mount(): void
    {
        if (isset($this->statutes_url)) {
            $this->statutes = collect(explode(',', $this->statutes_url))
                ->mapWithKeys(function ($status) {
                    return \App\TalepStatus::has($status) ? [$status => true] : [];
                })
                ->toArray();
        }
    }

    public function filterStatus(): void
    {
        if (! in_array(true, $this->statutes, true)) {
            $this->statutes_url = null;

            return;
        }

        $trueStatutes = collect($this->statutes)->filter(function ($q) {
            return $q == true;
        });

        $this->statutes_url = $trueStatutes->isNotEmpty() ? implode(',', $trueStatutes->keys()->toArray()) : null;
    }

    public function filterBranch($id): void
    {
        $this->branchName = \App\Models\Branch::where('id', $id)->pluck('name')?->first() ?? null;
        $this->branch = $id;
    }

    public function getTaleps()
    {
        $branch = $this->branch;
        $status_url = $this->statutes_url;
        $this->branchName ??= auth()->user()->staff_branch()->first()->name;

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
                } else {
                    $q->whereIn('status', [App\TalepStatus::bekleniyor, App\TalepStatus::cevapsiz, App\TalepStatus::mesgul]);
                }
            })
            ->where(function ($q) {
                if (! auth()->user()->hasRole('admin')) {
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
            'taleps' => $this->getTaleps(),
        ];
    }
};

?>
<div>
    <x-header title="Talep" subtitle="{{ $branchName ?? '' }}" separator progress-indicator>
        <x-slot:actions>
            <x-dropdown label="Şube" icon="tabler.building-store">
                @foreach (auth()->user()->staff_branch as $branch)
                    <x-menu-item @click="$wire.filterBranch({{ $branch->id }})" title="{{ $branch->name }}" />
                @endforeach
                <x-slot:trigger>
                    <x-button icon="tabler.building-store" class="btn-outline" label="{{ $branchName ?? 'Şube' }}"
                        responsive />
                </x-slot:trigger>
            </x-dropdown>
            <x-dropdown label="Filtrele" class="btn-outline">
                @foreach (\App\TalepStatus::cases() as $status)
                    <x-menu-item @click.stop="">
                        <x-checkbox wire:model="statutes.{{ $status->name }}" label="{{ $status->label() }}" />
                    </x-menu-item>
                @endforeach
                <x-menu-item>
                    <x-button icon="tabler.filter" class="btn-outline btn-sm" label="Filtrele"
                        wire:click="filterStatus" />
                </x-menu-item>
                <x-slot:trigger>
                    <x-button icon="tabler.filter" class="btn-outline" label="Durum" responsive />
                </x-slot:trigger>
            </x-dropdown>
        </x-slot:actions>
    </x-header>
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5 mb-5">
        @foreach ($taleps as $talep)
        <x-card subtitle="{{ $talep->status->label() }} - {{ $talep->branch->name }}" separator>
        <x-slot:title>
            <div class="flex flex-auto items-center">
                <x-badge class="badge-{{ $talep->status->color() }}" />
                <p class="ml-2">{{ $talep->name }}</p>
            </div>
        </x-slot:title>
        <x-slot:menu>
            <x-button icon="tabler.settings" wire:click="$dispatch('slide-over.open', {component: 'modals.talep.talep-modal', arguments: {'talep': {{ $talep->id }}}})" class="btn-outline btn-sm"
                responsive />
        </x-slot:menu>
        @if ($talep->status == \App\TalepStatus::randevu || $talep->status == \App\TalepStatus::sonra)
            <x-list-item :item="$talep">
                <x-slot:value>
                    İŞLEM TARİHİ
                </x-slot:value>
                <x-slot:actions>
                    {{ $talep->latestAction->date->format('d/m/Y H:i:s') }}
                </x-slot:actions>
            </x-list-item>
        @endif
        <x-list-item :item="$talep">
            <x-slot:value>
                TARİH
            </x-slot:value>
            <x-slot:actions>
                {{ $talep->dateHuman }}
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                Telefon
            </x-slot:value>
            <x-slot:actions>
                <x-button label="{{ $talep->phone }}" icon="o-phone" external link="tel:0{{ $talep->phone }}" />
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                Telefon - Düzensiz
            </x-slot:value>
            <x-slot:actions>
                <x-button label="{{ $talep->phone_long }}" icon="o-phone" external
                    link="tel:{{ $talep->phone_long }}" />
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                Çeşit
            </x-slot:value>
            <x-slot:actions>
                {{ $talep->type?->label() ?? '' }}
            </x-slot:actions>
        </x-list-item>
        <x-list-item :item="$talep">
            <x-slot:value>
                İŞLEM
            </x-slot:value>
            <x-slot:actions>
                {{ $talep->talep_processes_count }}
            </x-slot:actions>
        </x-list-item>
        "{{ $talep->message }}"
        <x-hr />
        @if ($talep->latestAction)
            <p>
                {{ $talep->latestAction->status }} - {{ $talep->latestAction->message }}
            </p>
            <p>{{ $talep->latestAction->user?->name ?? '' }}
                - {{ $talep->latestAction->date?->format('d/m/Y H:i:s') ?? 'TARİH YOK' }}</p>
        @endif
    </x-card>
        @endforeach
    </div>
    <x-pagination :rows="$taleps" />
</div>
