<?php

namespace App\Traits;

trait WithViewPlaceHolder
{
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
