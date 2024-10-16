<?php

use App\Models\Service;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public ?Service $service = null;

    public bool $show = false;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public $price = 0;

    #[Rule('required')]
    public $category_id;

    #[Rule('required')]
    public $gender = 0;

    #[Rule('required')]
    public $seans = 0;

    #[Rule('required|min:1')]
    public $duration = 1;

    #[Rule('required')]
    public $min_day = 0;

    #[Rule('required')]
    public $active = false;

    public function boot(): void
    {
        if (! $this->service) {
            $this->show = false;

            return;
        }

        $this->fill($this->service);
        $this->show = true;
    }

    public function save(): void
    {
        $this->service->update($this->validate());

        $this->reset();
        $this->dispatch('service-saved');
        $this->success('Hizmet güncellendi.');
    }
};
?>

<div>
    <x-modal wire:model="show" title="Güncelle" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-errors title="Hata!" icon="o-face-frown" />
            <livewire:components.form.category_dropdown wire:model="category_id" />
            <x-input label="Adı" wire:model="name" />
            <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
            <livewire:components.form.number_dropdown wire:model="seans" label="Seans Sayısı (Takas için)" max="100" includeZero="false" />
            <livewire:components.form.number_dropdown wire:model="duration" label="Hizmet Süresi (Dakika)" max="400" includeZero="false" />
            <livewire:components.form.gender_dropdown wire:model="gender" />
            <livewire:components.form.number_dropdown wire:model="min_day" label="Minimum Süre (Gün)" max="100" includeZero="false" />
            <x-toggle label="Aktif" wire:model="active" right />
            <x-slot:actions>
                <x-button label=" İptal" @click="$wire.show = false" />
                <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>