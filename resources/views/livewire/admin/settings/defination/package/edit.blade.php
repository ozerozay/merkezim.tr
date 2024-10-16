<?php

use App\Models\Package;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public ?Package $package = null;

    public bool $show = false;

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

    #[Rule('required')]
    public $active = false;

    public function boot(): void
    {
        if (! $this->package) {
            $this->show = false;

            return;
        }

        $this->fill($this->package);
        $this->show = true;
    }

    public function save(): void
    {
        $this->package->update($this->validate());

        $this->reset();
        $this->dispatch('package-saved');
        $this->success('Paket güncellendi.');
    }
};
?>

<div>
    <x-modal wire:model="show" title="Güncelle" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-errors title="Hata!" icon="o-face-frown" />
            <x-input label="Adı" wire:model="name" />
            <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
            <livewire:components.form.branch_multi_dropdown wire:model="branch_ids" />
            <livewire:components.form.gender_dropdown wire:model="gender" />
            <livewire:components.form.number_dropdown wire:model="buy_time" label="Ne kadar alınabilir ? (0 Sınırsız)" max="100" includeZero="true" />
            <x-toggle label="Aktif" wire:model="active" right />
            <x-slot:actions>
                <x-button label=" İptal" @click="$wire.show = false" />
                <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>