<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Models\ClientTaksit;
use App\Models\User;
use App\Peren;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTaksitAction
{
    use AsAction;

    public function handle($info): void
    {

        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $client = User::query()
                ->select('id', 'name', 'branch_id')
                ->where('id', $info['client_id'])
                ->first();

            foreach ($info['taksits'] as $taksit) {
                $tc = ClientTaksit::create([
                    'client_id' => $client->id,
                    'branch_id' => $client->branch_id,
                    'sale_id' => $info['sale_id'],
                    'total' => $taksit['price'],
                    'remaining' => $taksit['price'],
                    'status' => SaleStatus::success,
                    'date' => Peren::parseDateField($taksit['date']),
                ]);
                foreach ($taksit['locked'] as $tl) {
                    $tc->clientTaksitsLocks()->create([
                        'client_id' => $client->id,
                        'service_id' => $tl['service_id'],
                        'quantity' => $tl['quantity'],
                    ]);
                }
            }
        });
    }
}
