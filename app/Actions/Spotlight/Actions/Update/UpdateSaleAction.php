<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateSaleAction
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

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
