<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateSaleStatusAction
{
    use AsAction;

    public function handle($info): void
    {
        try {

            DB::beginTransaction();

            $sale = Sale::where('id', $info['id'])->first();

            CreateClientTimelineAction::run([
                'client_id' => $sale->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::update_sale_status,
                'message' => $sale->sale_no.' nolu satışın durumu düzenlendi'.$info['status']->label().' / '.$info['message'],
            ]);

            $sale->update([
                'status' => $info['status'],
                'freeze_date' => $info['freeze_date'],
            ]);

            $sale->clientServices()->update([
                'status' => $info['status'],
            ]);

            $sale->clientTaksits()->update([
                'status' => $info['status'],
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
