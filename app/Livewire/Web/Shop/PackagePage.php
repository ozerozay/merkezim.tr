<?php

namespace App\Livewire\Web\Shop;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.client')]
#[Title('Online Mağaza')]
class PackagePage extends Component
{
    #[Url]
    public string $search = '';

    #[Url(as: 'gender')]
    public array $gender_id = [];

    #[Url]
    public array $categories_id = [];

    public function hasFilters(): bool
    {
        return count($this->gender_id) || count($this->categories_id) || $this->search;
    }

    public function clearFilters(): void
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.client.shop.package-page', [
            'genders' => [
                [
                    'id' => 1,
                    'name' => 'KADIN',
                ],
                [
                    'id' => 2,
                    'name' => 'ERKEK',
                ],
            ],
            'categories' => [],
            'hasFilters' => $this->hasFilters(),
        ]);
    }
}
