<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateNoteAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info): void
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $client = GetClientByID::run(null, $info['client_id'], [], true);

            $client->client_notes()->create([
                'user_id' => $info['user_id'],
                'message' => $info['message'],
            ]);
        });
    }
}
