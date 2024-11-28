<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Actions\Spotlight\Actions\Product\UpdateProductStok;
use App\Exceptions\AppException;
use App\Models\Coupon;
use App\Models\Kasa;
use App\Models\Product;
use App\Models\SaleProduct;
use App\Models\User;
use App\Peren;
use App\TransactionType;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSaleProductAction
{
    use AsAction;

    public function handle($info): void
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $client = User::query()
                ->select(['id', 'name', 'branch_id'])
                ->where('id', $info['client_id'])
                ->first();

            $info['services'] = collect($info['services']);

            $sale_product = SaleProduct::create([
                'unique_id' => CreateUniqueID::run('sale_product'),
                'branch_id' => $client->branch_id,
                'user_id' => $info['user_id'],
                'client_id' => $client->id,
                'staff_ids' => $info['staff_ids'],
                'message' => $info['message'],
                'price' => $info['price'],
                'coupon_id' => $info['coupon_id'],
                'date' => $info['date'],
                'coupon_price' => $info['coupon_price'],
            ]);

            foreach ($info['services'] as $product) {
                $product_model = Product::select('id', 'name', 'stok')->where('id', $product['product_id'])->first();

                throw_if(! $product_model || $product_model->stok < $product['quantity'], new AppException('Yeterli stok bulunmuyor.Mevcut stok: '.$product_model->stok));

                $sale_product->saleProductItems()->create([
                    'product_id' => $product_model->id,
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'gift' => $product['gift'],
                ]);

                UpdateProductStok::run($product_model->id, $product['quantity']);
            }

            Coupon::where('id', $info['coupon_id'])->decrement('count', 1);

            foreach ($info['payments'] as $payment) {
                $kasa = Kasa::select('id', 'branch_id')->where('id', $payment['kasa_id'])->first();
                $sale_product->transactions()->create([
                    'kasa_id' => $kasa->id,
                    'branch_id' => $kasa->branch_id,
                    'user_id' => $info['user_id'],
                    'client_id' => $client->id ?? null,
                    'date' => $payment['date'],
                    'price' => $payment['price'],
                    'message' => $sale_product->unique_id.' nolu ürün satışından alınan peşinat',
                    'type' => TransactionType::product_pesinat,
                ]);
            }
        });
    }
}
