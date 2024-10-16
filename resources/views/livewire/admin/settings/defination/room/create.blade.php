<?php

use App\Models\ServiceRoom;
use App\Traits\HasCssClassAttribute;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use HasCssClassAttribute, Toast;

    #[Rule('required', as: 'Ad')]
    public string $name = '';

    #[Rule('required', as: 'Şube')]
    public $branch_id;

    #[Rule('required', as: 'Kategori')]
    public $category_ids = [];

    public bool $show = false;

    public string $label = 'Oluştur';

    public function showForm()
    {
        //$this->reset();
        $this->show = true;
    }

    #[On('branch-id-update')]
    public function update_id($id)
    {
        $this->branch_id = $id;
    }

    public function save()
    {
        ServiceRoom::create($this->validate());

        $this->show = false;
        $this->dispatch('room-saved');
        $this->success('Oda oluşturuldu.');
    }
};
?>
<div>
    <x-button :label="$label" wire:click="showForm" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Oda Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-errors title="Hata!" icon="o-face-frown" />
                <x-input label="Adı" wire:model="name" />
                <livewire:components.form.branch_dropdown wire:model="branch_id" />
                <livewire:components.form.category_multi_dropdown wire:model="category_ids" />
                <x-slot:actions>
                    <x-button label=" İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>