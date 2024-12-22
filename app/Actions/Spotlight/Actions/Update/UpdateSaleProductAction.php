<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
use App\Models\SaleProduct;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateSaleProductAction
{
    use AsAction;

    public function handle($info): void
    {

        try {

            DB::beginTransaction();

            $client_sale = SaleProduct::where('id', $info['id'])->first();

            CreateClientTimelineAction::run([
                'client_id' => $client_sale->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::update_sale_product,
                'message' => $info['message'],
            ]);

            $client_sale->update([
                'staff_ids' => $info['staff_ids'],
                'date' => $info['date'],
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }

    }
}
