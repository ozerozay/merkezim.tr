<?php

namespace App\Livewire\Settings\Defination\Package;

use App\Models\Package;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class PackageDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-package-update' => '$refresh',
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

    public function packages()
    {
        return Package::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->orderBy('branch_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('branch:id,name')
            ->withCount('items')
            ->paginate(10);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.package.package-defination', [
            'packages' => $this->packages(),
        ]);
    }
}
