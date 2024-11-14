<?php

use App\Models\Service;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $service_id;

    public $branch_id;

    public $gender;

    public Collection $services;

    #[\Livewire\Attributes\On('service-dropdown-update-branch')]
    public function updateBranch($branch): void
    {
        $this->branch_id = $branch;
        $this->getServices();
    }

    public function mount(): void
    {
        $this->services = collect();
        $this->getServices();
    }

    public function getServices(): void
    {
        $branch = $this->branch_id;
        $gender = $this->gender;
        if ($branch) {
            $this->services = Service::query()
                ->select(['id', 'name', 'category_id', 'active', 'gender'])
                ->whereIn('gender', [$gender, 0])
                ->where('active', true)
                ->orderBy('category_id', 'asc')
                ->whereHas('category.branches', function ($q) use ($branch) {
                    $q->whereIn('id', [$branch]);
                })
                ->with('category:id,name')
                ->get();
        }
    }
};

?>
<div>
    <x-choices-offline
        wire:model="service_id"
        :options="$services"
        option-sub-label="category.name"
        label="Hizmet"
        icon="o-magnifying-glass"
        single
        searchable/>
</div>
