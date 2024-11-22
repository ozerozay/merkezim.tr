<?php

namespace App\Livewire\Actions\Note;

use App\Models\User;
use Mary\View\Components\Toast;
use WireElements\Pro\Components\Spotlight\Spotlight;
use WireElements\Pro\Components\Spotlight\SpotlightAction;

class GetClientNotesAction extends SpotlightAction
{
    public int|User $client;

    public string $description;

    public function __construct($client, string $description = 'Notları Gör')
    {
        $this->client = $client;
        $this->description = $description;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function execute(Spotlight $spotlight): void
    {
        (new Toast)->success('test');
        $spotlight->close();
    }
}
