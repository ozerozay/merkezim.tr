<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Actions\Spotlight\Actions\User\CheckUserInstantApprove;
use App\Enum\PermissionType;
use App\Models\ClientService;
use App\Peren;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateServiceAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info): void
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $client = GetClientByID::run(null, $info['client_id'], [], true);

            foreach ($info['service_ids'] as $service) {
                ClientService::create([
                    'branch_id' => $info['branch_id'],
                    'client_id' => $info['client_id'],
                    'user_id' => $info['user_id'],
                    'service_id' => $service,
                    'sale_id' => $info['sale_id'],
                    'total' => $info['total'],
                    'remaining' => $info['remaining'],
                    'status' => CheckUserInstantApprove::run(auth()->user()->id, PermissionType::action_client_create_service) ? SaleStatus::success : SaleStatus::waiting,
                    'gift' => $info['gift'],
                    'message' => $info['message'],
                ]);
            }
        });
    }
}