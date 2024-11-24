<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateLabelAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info): void
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $client = GetClientByID::run(null, $info['client_id'], ['labels'], true);

            $client->labels = $info['labels'];
            $client->save();
        });
    }
}
