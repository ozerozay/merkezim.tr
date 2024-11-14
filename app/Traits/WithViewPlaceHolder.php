<?php

namespace App\Traits;

use Livewire\Attributes\Url;

trait WithViewPlaceHolder
{
    #[Url]
    public bool $view = true;

    public array $sortBy = ['column' => 'date', 'direction' => 'asc'];

    public array $expanded = [];

    public function placeholder()
    {
        return view('livewire.components.card.loading.loading');
    }

    public function changeView(): void
    {
        $this->view = ! $this->view;
    }
}
