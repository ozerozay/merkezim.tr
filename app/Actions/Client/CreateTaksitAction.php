<?php

namespace App\Actions\Client;

use App\Models\ClientTaksit;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTaksitAction
{
    use AsAction;

    public function handle($info)
    {
        return ClientTaksit::create($info);
    }
}
