<?php

namespace App\Actions\SaleProduct;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\SaleProduct;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteSaleProductAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $client_service = SaleProduct::where('id', $info['id'])->first();

            \App\Actions\Client\CreateClientTimelineAction::run([
                'client_id' => $client_service->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::delete_service,
                'message' => $info['message'],
            ]);

            // TODO: STOK GÜNCELLE

            $client_service->saleProductItems()->delete();
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
