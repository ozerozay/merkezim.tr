<?php

namespace App\Livewire\Settings\Defination\Kasa;

use App\Models\Kasa;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class KasaDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-kasa-update' => '$refresh',
    ];

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function userKasas()
    {
        return Kasa::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->orderBy('branch_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('branch:id,name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.kasa.kasa-defination', [
            'kasas' => $this->userKasas(),
        ]);
    }
}
