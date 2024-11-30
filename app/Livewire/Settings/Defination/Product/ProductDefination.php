<?php

namespace App\Livewire\Settings\Defination\Product;

use App\Models\Product;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class ProductDefination extends SlideOver
{
    use Toast, WithoutUrlPagination, WithPagination;

    public $listeners = [
        'defination-product-update' => '$refresh',
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

    public function products()
    {
        return Product::query()
            ->whereIn('branch_id', auth()->user()->staff_branches)
            ->orderBy('branch_id')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->orderBy('stok')
            ->with('branch:id,name')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.product.product-defination', [
            'products' => $this->products(),
        ]);
    }
}
