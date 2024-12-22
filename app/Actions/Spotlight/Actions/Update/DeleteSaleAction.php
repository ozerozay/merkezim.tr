<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteSaleAction
{
    use AsAction;

    public function handle($info): void
    {
        try {

            DB::beginTransaction();

            $client_sale = Sale::where('id', $info['id'])->first();

            CreateClientTimelineAction::run([
                'client_id' => $client_sale->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::delete_sale,
                'message' => $client_sale->sale_no.' nolu satış silindi'.' / '.$info['message'],
            ]);

            $client_sale->clientServices()->delete();
            $client_sale->clientTaksits()->delete();
            $client_sale->transactions()->delete();

            $client_sale->delete();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
