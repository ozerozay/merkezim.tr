<?php

use App\Models\Kasa;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public ?Kasa $kasa = null;

    public bool $show = false;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public $branch_id;

    #[Rule('required')]
    public bool $active;

    public function boot(): void
    {
        if (! $this->kasa) {
            $this->show = false;

            return;
        }

        $this->fill($this->kasa);
        $this->show = true;
    }

    public function save(): void
    {
        $this->kasa->update($this->validate());

        $this->reset();
        $this->dispatch('kasa-saved');
        $this->success('Kasa güncellendi.');
    }
};
?>
<div>
    <x-modal wire:model="show" title="Güncelle" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-input label="Adı" wire:model="name" />
            <livewire:components.form.branch_dropdown wire:model="branch_id" />
            <x-toggle label="Aktif" wire:model="active" right />
            <x-slot:actions>
                <x-button label="İptal" @click="$dispatch('kasa-cancel')" />
                <x-button label="Güncelle" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>