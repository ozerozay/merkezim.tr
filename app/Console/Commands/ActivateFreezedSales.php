<?php

namespace App\Console\Commands;

use App\Actions\Schedule\ActivateFreezedSalesAction;
use Illuminate\Console\Command;

class ActivateFreezedSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:activate-freezed-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ActivateFreezedSalesAction::run();
    }
}
