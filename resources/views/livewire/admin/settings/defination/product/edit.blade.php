<?php

use App\Models\Product;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public ?Product $product = null;

    public bool $show = false;

    #[Rule('required')]
    public String $name;

    #[Rule('Required')]
    public $branch_id;

    #[Rule('required')]
    public $price;

    #[Rule('required')]
    public $stok;

    #[Rule('required')]
    public $active = false;

    public function boot(): void
    {
        if (! $this->product) {
            $this->show = false;

            return;
        }

        $this->fill($this->product);
        $this->show = true;
    }

    public function save(): void
    {
        $this->product->update($this->validate());

        $this->reset();
        $this->dispatch('product-saved');
        $this->success('Ürün güncellendi.');
    }
};
?>

<div>
    <x-modal wire:model="show" title="Güncelle" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-errors title="Hata!" icon="o-face-frown" />
            <x-input label="Adı" wire:model="name" />
            <livewire:components.form.branch_dropdown wire:model="branch_id" />
            <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
            <livewire:components.form.number_dropdown wire:model="stok" label="Stok (Adet)" max="400" includeZero="true" />
            <x-toggle label="Aktif" wire:model="active" right />
            <x-slot:actions>
                <x-button label=" İptal" @click="$wire.show = false" />
                <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>