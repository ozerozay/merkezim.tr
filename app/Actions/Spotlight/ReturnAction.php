<?php

namespace App\Actions\Spotlight;

use WireElements\Pro\Components\Spotlight\Spotlight;
use WireElements\Pro\Components\Spotlight\SpotlightAction;

class ReturnAction extends SpotlightAction
{
    public function __construct() {}

    public function execute(Spotlight $spotlight): void
    {
        $spotlight->clearScope();
    }

    public function description(): string
    {
        return 'Geri Dön';
        // TODO: Implement description() method.
    }
}
