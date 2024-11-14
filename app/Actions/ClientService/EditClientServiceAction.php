<?php

namespace App\Actions\ClientService;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\ClientService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class EditClientServiceAction
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
                'type' => ClientTimelineType::edit_service,
                'message' => $client_service->service->name.' hizmet düzenlendi.Satış '.$info['sale_id'].' Seans:'.$client_service->remaining.' / '.$client_service->total.' / '.$info['message'],
            ]);

            $client_service->update([
                'remaining' => $info['remaining'],
                'total' => $info['total'],
                'sale_id' => $info['sale_id'],
                'service_id' => $info['service_id'],
            ]);

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
