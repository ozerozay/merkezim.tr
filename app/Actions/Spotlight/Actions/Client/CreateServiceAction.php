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
    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info, $approve) {
            $client = GetClientByID::run(null, $info['client_id'], [], true);

            $service_ids = [];

            foreach ($info['service_ids'] as $service) {
                $cs = ClientService::create([
                    'branch_id' => $info['branch_id'],
                    'client_id' => $info['client_id'],
                    'user_id' => $info['user_id'],
                    'service_id' => $service,
                    'sale_id' => $info['sale_id'],
                    'total' => $info['total'],
                    'remaining' => $info['remaining'],
                    'status' => $approve ? SaleStatus::success : (CheckUserInstantApprove::run(auth()->user()->id, PermissionType::action_client_create_service) ? SaleStatus::success : SaleStatus::waiting),
                    'gift' => $info['gift'],
                    'message' => $info['message'],
                ]);

                $service_ids[] = $cs->id;
            }

            \DB::commit();

            return $service_ids;

        }, $approve);
    }
}
