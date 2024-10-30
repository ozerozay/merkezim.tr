<?php

namespace App\Actions\SaleProduct;

use App\Actions\Helper\CreateProductSaleUniqueID;
use App\Exceptions\AppException;
use App\Models\Kasa;
use App\Models\Product;
use App\Models\SaleProduct;
use App\Models\User;
use App\Peren;
use App\TransactionType;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateSaleProductAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            DB::beginTransaction();

            $client = User::query()
                ->select(['id', 'name', 'branch_id'])
                ->where('id', $info['client_id'])
                ->first();

            $user = User::query()
                ->select(['id', 'name', 'branch_id'])
                ->where('id', $info['user_id'])
                ->first();

            $sale_product = SaleProduct::create([
                'unique_id' => CreateProductSaleUniqueID::run(),
                'client_id' => $client->id,
                'branch_id' => $client->branch_id,
                'user_id' => $user->id,
                'date' => $info['sale_date'],
                'staff_ids' => $info['staff_ids'],
                'message' => $info['message'],
                'price' => $info['price'],
            ]);

            $product_ids = $info['products'];

            foreach ($product_ids as $product) {
                $product_info = Product::where('id', $product['product_id'])->first();
                if ($product_info->stok < $product['quantity']) {
                    throw new AppException($product_info->name.' ürününden yeterli stok bulunmuyor.');
                    break;
                }

                $sale_product->saleProductItems()->create([
                    'product_id' => $product_info->id,
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'gift' => $product['gift'],
                ]);

                $product_info->stok = $product_info->stok - $product['quantity'];
                $product_info->save();
            }

            foreach ($info['cashes'] as $c) {
                $kasa = Kasa::select('id', 'branch_id')->where('id', $c['kasa'])->first();
                $sale_product->transactions()->create([
                    'kasa_id' => $c['kasa'],
                    'branch_id' => $kasa->branch_id,
                    'user_id' => $user->id,
                    'client_id' => $client->id,
                    'date' => Peren::parseDateField($c['date']),
                    'price' => $c['price'],
                    'message' => $sale_product->unique_id.' nolu ürün satışından alınan peşinat.',
                    'type' => TransactionType::product_pesinat,
                ]);
            }

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
