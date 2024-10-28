<?php

namespace App\Traits;

use Livewire\Attributes\Url;

trait ClientProfil
{
    public $client;

    public $data = null;

    #[Url]
    public $view = 'list';

    public function with()
    {
        return [
            'data' => $this->getTableData(),
            'headers' => $this->headers(),
        ];
    }

    public function placeholder()
    {
        return view('livewire.components.card.loading.loading');
    }

    public function changeView()
    {
        $this->view = $this->view == 'table' ? 'list' : 'table';
    }
}
