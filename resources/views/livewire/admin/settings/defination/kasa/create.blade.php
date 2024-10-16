<?php

use App\Models\Kasa;
use App\Traits\HasCssClassAttribute;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, HasCssClassAttribute;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public $branch_id;

    public bool $show = false;

    public string $label = 'Oluştur';

    public function showForm()
    {
        $this->reset();
        $this->show = true;
    }

    public function save()
    {
        Kasa::create($this->validate());

        $this->show = false;
        $this->dispatch('kasa-saved');
        $this->success('Kasa oluşturuldu.');
    }
};

?>

<div>
    <x-button :label="$label" wire:click="showForm" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Kasa Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-errors title="Hata!" icon="o-face-frown" />
                <x-input label="Adı" wire:model="name" />
                <livewire:components.form.branch_dropdown wire:model="branch_id" />
                <x-slot:actions>
                    <x-button label=" İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>