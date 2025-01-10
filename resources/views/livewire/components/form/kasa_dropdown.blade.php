<?php

use App\Models\Kasa;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $kasa_id;

    public $kasas;

    public $branch = null;

    public $label = 'Kasa';

    public function mount()
    {
        $query = Kasa::query()
            ->where('active', true)
            ->with('branch')
            ->orderBy('name');

        // Branch filtresi
        if ($this->branch) {
            $query->whereIn('branch_id', $this->branch);
        } else {
            $query->whereHas('branch', fn($q) => $q->whereIn('id', Auth::user()->staff_branches));
        }

        $kasalar = $query->get();

        // Şubelere göre grupla
        $this->kasas = $kasalar
            ->groupBy('branch.name')
            ->map(fn($group) => $group
                ->sortBy('name')
                ->map(fn($kasa) => [
                    'id' => $kasa->id,
                    'name' => $kasa->name
                ])
                ->values()
                ->toArray()
            )
            ->toArray();

        // Şubesiz kasaları ekle
        $subesizKasalar = $kasalar->whereNull('branch_id');
        if ($subesizKasalar->isNotEmpty()) {
            $this->kasas['Diğer Şubeler'] = $subesizKasalar
                ->sortBy('name')
                ->map(fn($kasa) => [
                    'id' => $kasa->id,
                    'name' => $kasa->name
                ])
                ->values()
                ->toArray();
        }
    }
};
?>

<div wire:key="kasa-dropdown-{{Str::random(10)}}">
    <x-select-group 
        wire:key="kasa-select-{{Str::random(10)}}"
        :label="$label"
        class="absolute"
        wire:model="kasa_id" 
        :options="$kasas"/>
</div>
