<?php

namespace App\Livewire\Modals;

use App\Actions\Spotlight\Actions\Get\GetProducts;
use App\Models\Product;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class SelectProductModal extends Modal
{
    use Toast;

    public int|User $client;

    public $product_ids = [];

    public $gift = false;

    public int $quantity = 1;

    public $product_collection;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->product_collection = collect();
    }

    public function search(string $value = ''): void
    {
        $selectedOption = Product::whereIn('id', $this->product_ids)->get();
        $this->product_collection = GetProducts::run([$this->client->branch_id])->merge($selectedOption);
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'product_ids' => $this->product_ids,
            'gift' => $this->gift,
            'quantity' => $this->quantity,
        ], [
            'product_ids' => 'required',
            'gift' => 'required|boolean',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->success('Ürün eklendi.');
        $this->dispatch('modal-product-added', $validator->validated());
        $this->product_ids = [];
        $this->reset('quantity', 'gift');
    }

    public function render()
    {
        return view('livewire.spotlight.modals.select-product-modal');
    }
}
