<?php

namespace App\Livewire\Settings\Defination\SaleType;

use App\Models\SaleType;
use Livewire\Features\SupportPagination\WithoutUrlPagination;
use Livewire\WithPagination;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleTypeDefination extends SlideOver
{
    use \Mary\Traits\Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-sale-type-update' => '$refresh',
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

    public function saleTypes()
    {
        return SaleType::query()
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.sale-type.sale-type-defination', [
            'sale_types' => $this->saleTypes(),
        ]);
    }
}
