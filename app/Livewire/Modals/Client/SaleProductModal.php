<?php

namespace App\Livewire\Modals\Client;

use App\Actions\Spotlight\Actions\Update\DeleteSaleProductAction;
use App\Actions\Spotlight\Actions\Update\UpdateSaleProductAction;
use App\Models\SaleProduct;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class SaleProductModal extends SlideOver
{
    use Toast;

    public int|SaleProduct $product;

    public $group = 'group0';

    public $messageDelete;

    public $messageEdit;

    public $product_sale_date;

    public $product_sale_staffs = [];

    public function mount(SaleProduct $product): void
    {
        $this->product = $product;

        $this->product_sale_date = $product->date;
        $this->product_sale_staffs = $product->staff_ids;
    }

    public function edit(): void
    {
        $validator = \Validator::make([
            'id' => $this->product->id,
            'message' => $this->messageEdit,
            'date' => $this->product_sale_date,
            'staff_ids' => $this->product_sale_staffs,
        ], [
            'id' => 'required',
            'message' => 'required',
            'date' => 'required',
            'staff_ids' => 'nullable',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        UpdateSaleProductAction::run($validator->validated());

        $this->dispatch('refresh-client-product-sales');
        $this->success('Ürün satışı güncellendi.');
        $this->close();
    }

    public function delete(): void
    {
        $validator = \Validator::make([
            'id' => $this->product->id,
            'message' => $this->messageDelete,
        ], [
            'id' => 'required|exists:sale_product',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        DeleteSaleProductAction::run($validator->validated());

        $this->dispatch('refresh-client-product-sales');
        $this->success('Ürün satışı silindi, stok güncellendi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.client.sale-product-modal');
    }
}
