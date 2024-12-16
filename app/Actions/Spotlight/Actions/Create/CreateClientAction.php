<?php

namespace App\Actions\Spotlight\Actions\Create;

use App\Models\User;
use App\Peren;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateClientAction
{
    use AsAction, StrHelper;

    /**
     * @throws ToastException
     */
    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $info['name'] = $this->strUpper($info['name']);
            $info['unique_id'] = CreateUniqueID::run('user');
            $info['first_login'] = false;

            $client = User::create($info);

            \DB::commit();

            return [$client->id];
        }, $approve);
    }
}
