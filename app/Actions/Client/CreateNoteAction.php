<?php

namespace App\Actions\Client;

use App\Actions\Spotlight\Actions\Client\GetClientByID;
use App\Peren;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateNoteAction
{
    use AsAction, StrHelper;

    /**
     * @throws ToastException
     */
    public function handle(array $info): void
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $client = GetClientByID::run(null, $info['client_id'], true);

            $client->client_notes()->create([
                'user_id' => $info['user_id'],
                'message' => $info['message'],
            ]);
        });
    }
}
