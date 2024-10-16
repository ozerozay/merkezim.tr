<?php

namespace App\Actions\Client;

use App\Actions\User\CheckApproveAction;
use App\Exceptions\AppException;
use App\SaleStatus;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateServicesAction
{
    use AsAction;

    public function handle($info, $user)
    {
        try {
            DB::beginTransaction();

            foreach ($info['service_ids'] as $service) {
                CreateServiceAction::run([
                    'branch_id' => $info['branch_id'],
                    'client_id' => $info['client_id'],
                    'user_id' => $user,
                    'service_id' => $service,
                    'sale_id' => $info['sale_id'],
                    'total' => $info['total'],
                    'remaining' => $info['remaining'],
                    'status' => CheckApproveAction::run(auth()->user()->id) ? SaleStatus::success : SaleStatus::waiting,
                    'gift' => $info['gift'],
                    'message' => $info['message'],
                ]);
            }

            DB::commit();
        } catch (AppException $e) {
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new AppException('Hata oluştu.');
        }

    }
}
