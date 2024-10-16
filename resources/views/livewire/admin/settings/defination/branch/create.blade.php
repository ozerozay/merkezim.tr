<?php

use App\Actions\Branch\BranchCreateAction;
use App\Traits\HasCssClassAttribute;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use HasCssClassAttribute, Toast;

    public string $name = '';

    public bool $show = false;

    public string $label = 'Oluştur';

    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('branches', 'name'),
            ],
        ];
    }

    public function save(): void
    {
        $branch = BranchCreateAction::run($this->validate());

        $this->show = false;
        $this->resetExcept('label', 'class');
        $this->dispatch('branch-saved', id: $branch->id);
        $this->success('Şube eklendi.');
    }
};

?>

<div>
    <x-button :label="$label" @click="$wire.show = true" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Şube Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-input label="Adı" wire:model="name" />

                <x-slot:actions>
                    <x-button label="İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>