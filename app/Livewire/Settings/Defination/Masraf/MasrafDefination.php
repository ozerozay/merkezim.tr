<?php

namespace App\Livewire\Settings\Defination\Masraf;

use App\Models\Masraf;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class MasrafDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-masraf-update' => '$refresh',
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

    public function masrafs()
    {
        return Masraf::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->orderBy('branch_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('branch:id,name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.masraf.masraf-defination', [
            'masrafs' => $this->masrafs(),
        ]);
    }
}
