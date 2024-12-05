<?php

namespace App\Actions\Spotlight\Actions\Talep;

use App\Models\Talep;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class EditTalepAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $talep = Talep::where('id', $info['id'])->first();

            $talep->update([
                'name' => $info['name'],
                'phone' => $info['phone'],
                'message' => $info['message'],
                'type' => $info['type'],
            ]);
        });

    }
}
