<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Peren;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class EditClientAction
{
    use AsAction, StrHelper;

    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $info['name'] = $this->strUpper($info['name']);

            $client = \App\Models\User::find($info['client_id']);
            $client->update($info);
            \DB::commit();

            return [$client->id];
        }, $approve);
    }
}
