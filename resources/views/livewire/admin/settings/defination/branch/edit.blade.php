<?php

use App\Actions\Branch\BranchEditAction;
use App\Models\Branch;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public ?Branch $branch = null;

    public bool $show = false;

    public string $name = '';

    public bool $active;

    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('branches', 'name')->ignore($this->branch),
            ],
            'active' => 'required|boolean',
        ];
    }

    public function boot(): void
    {
        if (! $this->branch) {
            $this->show = false;

            return;
        }

        $this->fill($this->branch);
        $this->show = true;
    }

    public function save(): void
    {
        //$this->branch->update($this->validate());

        BranchEditAction::run($this->validate(), $this->branch);

        $this->reset();
        $this->dispatch('branch-saved');
        $this->success('Şube güncellendi.');
    }
};
?>

<div>
    <x-modal wire:model="show" title="Güncelle" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-input label="Ad" wire:model="name" />
            <x-toggle label="Aktif" wire:model="active" right />

            <x-slot:actions>
                <x-button label="İptal" @click="$dispatch('branch-cancel')" />
                <x-button label="Güncelle" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>