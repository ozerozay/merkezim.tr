<?php

namespace App\Livewire\Settings\Shop;

use App\Models\ShopPackage;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ShopPackageSettings extends SlideOver
{
    use \Livewire\WithoutUrlPagination, Toast, WithPagination;

    public $listeners = [
        'settings-shop-package-update' => '$refresh',
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

    public function getPackages()
    {
        return ShopPackage::query()
            ->whereHas('package', function ($q) {
                $q->whereIn('branch_id', auth()->user()->staff_branches);
            })
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('package:id,name,branch_id', 'package.branch:id,name')
            ->paginate(10);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.shop.shop-package-settings', [
            'packages' => $this->getPackages(),
        ]);
    }
}
