<?php

namespace App\Actions\Spotlight\Actions\Talep;

use App\Models\Talep;
use App\Models\TalepProcess;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTalepProcessAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            TalepProcess::create($info);

            Talep::where('id', $info['talep_id'])->update([
                'status' => $info['status'],
            ]);
        });
    }
}
