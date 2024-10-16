<?php

use App\Models\Product;
use App\Traits\HasCssClassAttribute;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {

    use Toast, HasCssClassAttribute;

    #[Rule('required')]
    public String $name;

    #[Rule('Required')]
    public $branch_id;

    #[Rule('required')]
    public $price;

    #[Rule('required')]
    public $stok;

    public bool $show = false;

    public string $label = 'Oluştur';

    public function showForm()
    {
        $this->reset();
        $this->show = true;
    }

    public function save()
    {
        Product::create($this->validate());

        $this->show = false;
        $this->dispatch('product-saved');
        $this->success('Ürün oluşturuldu.');
    }
};

?>

<div>
    <x-button :label="$label" wire:click="showForm" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Ürün Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-errors title="Hata!" icon="o-face-frown" />
                <x-input label="Adı" wire:model="name" />
                <livewire:components.form.branch_dropdown wire:model="branch_id" />
                <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
                <livewire:components.form.number_dropdown wire:model="stok" label="Stok (Adet)" max="400" includeZero="true" />
                <x-slot:actions>
                    <x-button label=" İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>