<?php

use App\Models\Package;
use App\Traits\HasCssClassAttribute;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, HasCssClassAttribute;

    #[Rule('required')]
    public $name;

    #[Rule('required')]
    public $price;

    #[Rule('required')]
    public $gender = 0;

    #[Rule('required')]
    public $branch_ids = [];

    #[Rule('required')]
    public $buy_time = 0;

    public bool $show = false;

    public string $label = 'Oluştur';

    public function showForm()
    {
        $this->reset();
        $this->show = true;
    }

    public function save()
    {
        Package::create($this->validate());

        $this->show = false;
        $this->dispatch('package-saved');
        $this->success('Paket oluşturuldu.');
    }
};
?>
<div>
    <x-button :label="$label" wire:click="showForm" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Paket Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-errors title="Hata!" icon="o-face-frown" />
                <x-input label="Adı" wire:model="name" />
                <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
                <livewire:components.form.branch_multi_dropdown wire:model="branch_ids" />
                <livewire:components.form.gender_dropdown wire:model="gender" />
                <livewire:components.form.number_dropdown wire:model="buy_time" label="Ne kadar alınabilir ? (0 Sınırsız)" max="100" includeZero="true" />
                <x-slot:actions>
                    <x-button label=" İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>