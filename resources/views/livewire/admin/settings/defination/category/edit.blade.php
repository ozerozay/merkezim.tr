<?php

use App\Models\Branch;
use App\Models\ServiceCategory;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public ?ServiceCategory $category = null;

    public bool $show = false;

    public string $name = '';

    public $price = 0;

    public $earn = 0;

    public $branch_ids = [];

    public bool $active;

    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('service_categories', 'name')->ignore($this->category)
            ],
            'price' => [
                'required',
                'decimal:0,2'
            ],
            'earn' => ['required'],
            'branch_ids' => ['required'],
            'active' => 'required|boolean'
        ];
    }

    public function boot(): void
    {
        if (! $this->category) {
            $this->show = false;

            return;
        }

        $this->fill($this->category);
        $this->show = true;
    }

    public function save(): void
    {
        $this->category->update($this->validate());

        $this->reset();
        $this->dispatch('category-saved');
        $this->success('Kategori güncellendi.');
    }
};
?>
<div>
    <x-modal wire:model="show" title="Güncelle" persistent>
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-input label="Adı" wire:model="name" />
            <x-input label="Seans Fiyatı" wire:model="price" suffix="₺" money />
            <livewire:components.form.branch_multi_dropdown wire:model="branch_ids" />
            <livewire:components.form.number_dropdown wire:model="earn" label="Kaç seans kullanılırsa ödül kazanılacak ?" max="100" includeZero="true" />
            <x-toggle label="Aktif" wire:model="active" right />
            <x-slot:actions>
                <x-button label="İptal" @click="$dispatch('category-cancel')" />
                <x-button label="Güncelle" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>