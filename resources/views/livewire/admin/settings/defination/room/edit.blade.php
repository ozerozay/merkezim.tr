<?php

use App\Models\ServiceRoom;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public ?ServiceRoom $room = null;

    public bool $show = false;

    public string $name = '';

    public $category_ids = [];

    public $branch_id = 0;

    public bool $active;

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'branch_id' => ['required'],
            'category_ids' => ['required'],
            'active' => 'required|boolean'
        ];
    }

    public function boot(): void
    {
        if (! $this->room) {
            $this->show = false;

            return;
        }

        $this->fill($this->room);
        $this->show = true;
    }

    public function save(): void
    {
        $this->room->update($this->validate());

        $this->reset();
        $this->dispatch('room-saved');
        $this->success('Oda güncellendi.');
    }
};
?>
<div>
    <x-modal wire:model="show" title="Güncelle" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-input label="Adı" wire:model="name" />
            <livewire:components.form.branch_dropdown wire:model="branch_id" />
            <livewire:components.form.category_multi_dropdown wire:model="category_ids" />
            <x-toggle label="Aktif" wire:model="active" right />
            <x-slot:actions>
                <x-button label="İptal" @click="$dispatch('room-cancel')" />
                <x-button label="Güncelle" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>