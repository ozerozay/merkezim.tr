<?php

namespace App\Livewire\Settings\Shop;

use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ShopService extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'settings-shop-service-update' => '$refresh',
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

    public function getServices()
    {
        return \App\Models\ShopService::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->orderBy('branch_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->with('service:id,name', 'branch:id,name')
            ->paginate(10);

    }

    public function render()
    {
        return view('livewire.spotlight.settings.shop.shop-service', [
            'services' => $this->getServices(),
        ]);
    }
}
