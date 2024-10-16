<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\ClientService;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UseServiceAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $services = ClientService::whereIn('id', $info['service_ids'])->get();

            foreach ($services as $service) {
                if ($service->remaining >= $info['seans']) {
                    $service->clientServiceUses()->create([
                        'user_id' => $info['user_id'],
                        'client_id' => $info['client_id'],
                        'seans' => $info['seans'],
                        'message' => $info['message'],
                    ]);

                    $service->remaining -= $info['seans'];
                    $service->save();
                } else {
                    throw new AppException('Hizmette yeterli seans bulunmuyor.');
                    break;
                }
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
