<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\ClientService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateClientServiceStatusAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $client_service = ClientService::where('id', $info['id'])->first();

            CreateClientTimelineAction::run([
                'client_id' => $client_service->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::update_service_status,
                'message' => $client_service->service->name.' hizmet durumu düzenlendi.',
            ]);

            $client_service->update([
                'status' => $info['status'],
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
