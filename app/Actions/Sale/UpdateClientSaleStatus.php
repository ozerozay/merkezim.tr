<?php

namespace App\Actions\Sale;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateClientSaleStatus
{
    use AsAction;

    public function handle($info): void
    {
        try {
            DB::beginTransaction();

            $sale = Sale::where('id', $info['id'])->first();

            \App\Actions\Client\CreateClientTimelineAction::run([
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
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
