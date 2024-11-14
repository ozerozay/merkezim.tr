<?php

namespace App\Actions\Sale;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteClientSale
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $client_sale = Sale::where('id', $info['id'])->first();

            \App\Actions\Client\CreateClientTimelineAction::run([
                'client_id' => $client_sale->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::delete_sale,
                'message' => $client_sale->sale_no.' nolu satış silindi'.' / '.$info['message'],
            ]);

            $client_sale->clientServices()->delete();
            $client_sale->clientTaksits()->delete();
            $client_sale->transactions()->delete();

            // TODO: KASA İŞLEMLERİ SİLİNECEK

            $client_sale->delete();

            DB::commit();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
