<?php

namespace App\Livewire\Modals\Client;

use App\Models\SaleProduct;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleProductModal extends SlideOver
{
    public int|SaleProduct $product;

    public $group = 'group1';

    public $messageDelete;

    public function mount(SaleProduct $product): void
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.sale-product-modal');
    }
}
