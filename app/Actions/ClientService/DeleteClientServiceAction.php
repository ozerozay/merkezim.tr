<?php

namespace App\Actions\ClientService;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\ClientService;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteClientServiceAction
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
                'type' => ClientTimelineType::delete_service,
                'message' => $client_service->service->name.' hizmeti silindi. Seans:'.$client_service->remaining.' / '.$client_service->total.' / '.$info['message'],
            ]);

            $client_service->delete();

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
