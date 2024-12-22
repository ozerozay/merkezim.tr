<?php

namespace App\Actions\Spotlight\Actions\Update;

use App\Actions\Spotlight\Actions\Create\CreateClientTimelineAction;
use App\ClientTimelineType;
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

            $client_product_sale = SaleProduct::where('id', $info['id'])->first();

            CreateClientTimelineAction::run([
                'client_id' => $client_product_sale->client_id,
                'user_id' => auth()->user()->id,
                'type' => ClientTimelineType::delete_sale_product,
                'message' => $info['message'],
            ]);

            foreach ($client_product_sale->saleProductItems as $item) {
                $item->product()->increment('stok', $item->quantity);
            }

            $client_product_sale->saleProductItems()->delete();
            $client_product_sale->transactions()->delete();

            $client_product_sale->delete();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.');
        }
    }
}
