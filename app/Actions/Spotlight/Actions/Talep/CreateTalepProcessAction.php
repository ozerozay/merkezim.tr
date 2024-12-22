<?php

namespace App\Actions\Spotlight\Actions\Talep;

use App\Models\Talep;
use App\Models\TalepProcess;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateTalepProcessAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            TalepProcess::create($info);

            Talep::where('id', $info['talep_id'])->update([
                'status' => $info['status'],
            ]);
            \DB::commit();
        });
    }
}
