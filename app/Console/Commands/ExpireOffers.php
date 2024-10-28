<?php

namespace App\Console\Commands;

use App\Actions\Schedule\OfferMakeCancelAction;
use Illuminate\Console\Command;

class ExpireOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Süresi dolan teklifleri iptal eder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        OfferMakeCancelAction::run();
    }
}
