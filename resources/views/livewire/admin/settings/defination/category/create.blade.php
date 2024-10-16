<?php

use App\Models\ServiceCategory;
use App\Traits\HasCssClassAttribute;
use Barryvdh\Debugbar\Facades\Debugbar as FacadesDebugbar;
use DebugBar\DebugBar;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, HasCssClassAttribute;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required|decimal:0,2')]
    public $price = 0;

    #[Rule('required')]
    public $earn = 0;

    #[Rule('required')]
    public $branch_ids = [];

    public bool $show = false;

    public string $label = 'Oluştur';

    public function showForm()
    {
        $this->reset();
        $this->show = true;
    }

    public function save()
    {
        ServiceCategory::create($this->validate());

        $this->show = false;
        $this->dispatch('category-saved');
        $this->success('Kategori oluşturuldu.');
    }
};

?>

<div>
    <x-button :label="$label" wire:click="showForm" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Kategori Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-errors title="Hata!" icon="o-face-frown" />
                <x-input label="Adı" wire:model="name" />
                <x-input label="Seans Fiyatı" wire:model="price" suffix="₺" money />
                <livewire:components.form.branch_multi_dropdown wire:model="branch_ids" />
                <livewire:components.form.number_dropdown wire:model="earn" label="Kaç seans kullanılırsa ödül kazanılacak ?" max="100" includeZero="true" />
                <x-slot:actions>
                    <x-button label=" İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>