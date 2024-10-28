<?php

namespace App\Console\Commands;

use App\Actions\Schedule\ExpireSalesAction;
use Illuminate\Console\Command;

class ExpireSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-sales';

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
        ExpireSalesAction::run();
    }
}
