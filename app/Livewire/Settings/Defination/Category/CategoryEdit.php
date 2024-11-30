<?php

namespace App\Livewire\Settings\Defination\Category;

use App\Models\ServiceCategory;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CategoryEdit extends SlideOver
{
    use \App\Traits\StrHelper, \Mary\Traits\Toast;

    public int|ServiceCategory $category;

    public ?string $name = null;

    public ?bool $active = null;

    public array $branch_ids = [];

    public ?int $earn = 0;

    public function mount(ServiceCategory $category)
    {
        $this->category = $category;
        $this->fill($category);
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'name' => $this->strUpper($this->name),
            'branch_ids' => $this->branch_ids,
            'earn' => $this->earn,
            'price' => 0,
            'active' => $this->active,
        ], [
            'branch_ids' => 'required',
            'name' => ['required'],
            'earn' => 'required|integer',
            'price' => 'required',
            'active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->category->update($validator->validated());

        $this->success('Kategori düzenlendi.');
        $this->close(andDispatch: ['defination-category-update']);
    }

    public function render()
    {
        return view('livewire.spotlight.settings.defination.category.category-edit');
    }
}
