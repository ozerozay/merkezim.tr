<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Actions\Spotlight\Actions\Product\UpdateProductStok;
use App\Exceptions\AppException;
use App\Models\Adisyon;
use App\Models\Coupon;
use App\Models\Kasa;
use App\Models\Product;
use App\Models\User;
use App\Peren;
use App\TransactionType;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAdisyonAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {

            $client = User::query()
                ->select(['id', 'name', 'branch_id'])
                ->where('id', $info['client_id'])
                ->first();

            $info['services'] = collect($info['services']);

            $adisyon = Adisyon::create([
                'unique_id' => CreateUniqueID::run('adisyon'),
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

            $services = $info['services']->where('type', 'service')->all();
            $packages = $info['services']->where('type', 'package')->all();
            $products = $info['services']->where('type', 'product')->all();
            $payments = $info['payments'];

            foreach ($services as $service) {
                $adisyon->adisyonServices()->create([
                    'service_id' => $service['service_id'],
                    'total' => $service['quantity'],
                    'gift' => $service['gift'],
                ]);
            }

            foreach ($packages as $package) {
                $adisyon->adisyonPackages()->create([
                    'package_id' => $package['package_id'],
                    'total' => $package['quantity'],
                    'gift' => $package['gift'],
                ]);
            }

            foreach ($products as $product) {
                $product_model = Product::select('id', 'name', 'stok')->where('id', $product['product_id'])->first();

                throw_if(! $product_model || $product_model->stok < $product['quantity'], new AppException('Yeterli stok bulunmuyor.Mevcut stok: '.$product_model->stok));

                $adisyon->adisyonProducts()->create([
                    'product_id' => $product_model->id,
                    'total' => $product['quantity'],
                    'gift' => $product['gift'],
                ]);

                UpdateProductStok::run($product_model->id, $product['quantity']);
            }

            $kupon = Coupon::where('id', $info['coupon_id'])->decrement('count', 1);

            foreach ($payments as $payment) {
                $kasa = Kasa::select('id', 'branch_id')->where('id', $payment['kasa_id'])->first();
                $adisyon->transactions()->create([
                    'kasa_id' => $kasa->id,
                    'branch_id' => $kasa->branch_id,
                    'user_id' => $info['user_id'],
                    'client_id' => $client->id ?? null,
                    'date' => $payment['date'],
                    'price' => $payment['price'],
                    'message' => $adisyon->unique_id.' nolu adisyondan alınan peşinat',
                    'type' => TransactionType::adisyon_pesinat,
                ]);
            }
        });
    }
}
