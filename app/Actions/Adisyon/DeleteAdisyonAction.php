<?php

namespace App\Actions\Adisyon;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\Adisyon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteAdisyonAction
{
    use AsAction;

    public function handle($info): void
    {

        try {
            DB::beginTransaction();

            $client_service = Adisyon::where('id', $info['id'])->first();

            if ($client_service->client) {
                \App\Actions\Client\CreateClientTimelineAction::run([
                    'client_id' => $client_service->client_id,
                    'user_id' => auth()->user()->id,
                    'type' => ClientTimelineType::delete_adisyon,
                    'message' => $info['message'],
                ]);
            }

            // TODO: ÜRÜN SİLME

            $client_service->adisyonServices()->delete();
            $client_service->transactions()->delete();

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
