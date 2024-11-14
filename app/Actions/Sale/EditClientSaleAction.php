<?php

namespace App\Actions\Sale;

use App\ClientTimelineType;
use App\Exceptions\AppException;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class EditClientSaleAction
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
                'type' => ClientTimelineType::update_sale,
                'message' => $info['message'],
            ]);

            $client_sale->update([
                'sale_type_id' => $info['sale_type_id'],
                'expire_date' => $info['expire_date'],
                'date' => $info['date'],
                'staffs' => $info['staffs'],
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
