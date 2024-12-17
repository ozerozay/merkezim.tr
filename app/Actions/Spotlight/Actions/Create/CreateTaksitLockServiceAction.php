<?php

namespace App\Actions\Spotlight\Actions\Create;

use App\Models\ClientService;
use App\Models\ClientTaksit;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTaksitLockServiceAction
{
    use AsAction;

    public function handle($taksit)
    {
        try {

            $taksit = ClientTaksit::find($taksit)->with('clientTaksitsLocks.service');

            foreach ($taksit->clientTaksitsLocks as $lock) {
                ClientService::create([
                    'branch_id' => $taksit->branch_id,
                    'client_id' => $taksit->client_id,
                    'service_id' => $lock->service_id,
                    'taksit_id' => $taksit->id,
                    'total' => $lock->quantity,
                    'remaining' => $lock->quantity,
                    'gift' => false,
                    'status' => SaleStatus::success,
                ]);
                $lock->used = true;
                $lock->save();
            }

        } catch (\Throwable $e) {

        }
    }
}
