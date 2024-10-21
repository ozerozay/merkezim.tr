<?php

namespace App\Actions\Label;

use App\Models\Label;
use Lorisleiva\Actions\Concerns\AsAction;

class GetActiveLabels
{
    use AsAction;

    public function handle()
    {
        return Label::where('active', true)->orderBy('name', 'asc')->get();
    }
}
