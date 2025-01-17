<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\User;
use App\Peren;
use App\SaleStatus;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateManuelTaksitAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $client = User::query()
                ->select('id', 'name', 'branch_id')
                ->where('id', $info['client_id'])
                ->first();

            foreach ($info['taksits'] as $taksit) {
                $tc = CreateTaksitAction::run([
                    'client_id' => $client->id,
                    'branch_id' => $client->branch_id,
                    'sale_id' => $info['sale_id'],
                    'total' => $taksit['price'],
                    'remaining' => $taksit['price'],
                    'status' => SaleStatus::success,
                    'date' => Peren::parseDateField($taksit['date']),
                ]);
                foreach ($taksit['locked'] as $tl) {
                    foreach ($tl['service_ids'] as $si) {
                        $tc->clientTaksitsLocks()->create([
                            'client_id' => $client->id,
                            'service_id' => $si,
                            'quantity' => $tl['quantity'],
                        ]);
                    }
                }
            }

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
