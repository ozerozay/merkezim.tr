<?php

use App\Models\Service;
use App\Traits\HasCssClassAttribute;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {

    use Toast, HasCssClassAttribute;

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

    public bool $show = false;

    public string $label = 'Oluştur';

    public function showForm()
    {
        $this->reset();
        $this->show = true;
    }

    public function save()
    {
        Service::create($this->validate());

        $this->show = false;
        $this->dispatch('service-saved');
        $this->success('Hizmet oluşturuldu.');
    }
};
?>

<div>
    <x-button :label="$label" wire:click="showForm" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Hizmet Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-errors title="Hata!" icon="o-face-frown" />
                <livewire:components.form.category_dropdown wire:model="category_id" />
                <x-input label="Adı" wire:model="name" />
                <x-input label="Fiyatı" wire:model="price" suffix="₺" money />
                <livewire:components.form.number_dropdown wire:model="seans" label="Seans Sayısı (Takas için)" max="100" includeZero="false" />
                <livewire:components.form.number_dropdown wire:model="duration" label="Hizmet Süresi (Dakika)" max="400" includeZero="false" />
                <livewire:components.form.gender_dropdown wire:model="gender" />
                <livewire:components.form.number_dropdown wire:model="min_day" label="Minimum Süre (Gün)" max="100" includeZero="false" />
                <x-slot:actions>
                    <x-button label=" İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>