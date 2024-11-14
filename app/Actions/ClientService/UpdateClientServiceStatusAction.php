<?php

namespace App\Actions\ClientService;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\ClientService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateClientServiceStatusAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $client_service = ClientService::where('id', $info['id'])->first();

            \App\Actions\Client\CreateClientTimelineAction::run([
                'client_id' => $client_service->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::update_service_status,
                'message' => $client_service->service->name.' hizmet durumu düzenlendi.'.$info['status'].' / '.$info['message'],
            ]);

            $client_service->update([
                'status' => $info['status'],
            ]);

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
