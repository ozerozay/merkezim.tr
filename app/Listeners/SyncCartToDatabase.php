<?php

namespace App\Listeners;

use App\Managers\ShoppingCartManager;
use Illuminate\Auth\Events\Login;

class SyncCartToDatabase
{
    protected $cartManager;

    /**
     * Create the event listener.
     */
    public function __construct(ShoppingCartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $this->cartManager->syncSessionToDatabase();
    }
}
