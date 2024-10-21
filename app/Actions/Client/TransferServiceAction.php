<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\ClientService;
use App\Models\User;
use App\SaleStatus;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class TransferServiceAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $services = ClientService::whereIn('id', $info['service_ids'])->get();

            $aktar_client = User::select(['id', 'name', 'branch_id'])->first();

            throw_if(! $aktar_client, new AppException('Aktarılacak danışan bulunamadı.'));

            foreach ($services as $service) {
                if ($service->remaining >= $info['seans']) {
                    $service->clientServiceUses()->create([
                        'user_id' => $info['user_id'],
                        'client_id' => $info['client_id'],
                        'seans' => $info['seans'],
                        'message' => 'AKTARIM - '.$info['message'],
                    ]);

                    $service->remaining -= $info['seans'];
                    $service->save();

                    ClientService::create([
                        'branch_id' => $aktar_client->branch_id,
                        'client_id' => $aktar_client->id,
                        'service_id' => $service->service->id,
                        'sale_id' => $info['aktar_sale_id'],
                        'package_id' => $service->package_id,
                        'total' => $info['seans'],
                        'remaining' => $info['seans'],
                        'gift' => $service->gift,
                        'message' => 'AKTARIM - '.$info['message'],
                        'user_id' => $info['user_id'],
                        'status' => SaleStatus::success,
                    ]);
                } else {
                    throw new AppException('Hizmette yeterli seans bulunmuyor.');
                    break;
                }
            }

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
