<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Actions\Spotlight\Actions\Get\GetSaleNoAction;
use App\Exceptions\AppException;
use App\Models\ClientService;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Sale;
use App\Models\User;
use App\OfferStatus;
use App\Peren;
use App\SaleStatus;
use App\TransactionType;
use Lorisleiva\Actions\Concerns\AsAction;

class FinishClientOfferAction
{
    use AsAction;

    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            dump($info);
            $offer = Offer::where('id', $info['offer_id'])->with('items')->first();
            $client = User::select('id', 'name', 'branch_id')->where('id', $info['client_id'])->first();

            $sale = Sale::create([
                'offer_id' => $offer->id,
                'branch_id' => $client->branch_id,
                'unique_id' => CreateUniqueID::run('sale'),
                'client_id' => $client->id,
                'user_id' => $info['user_id'],
                'sale_type_id' => $info['sale_type_id'],
                'date' => $info['date'],
                'status' => SaleStatus::success,
                'price' => $info['sale_price'],
                'price_real' => $info['price_real'],
                'coupon_id' => $info['coupon_id'],
                'staffs' => $info['staff_ids'],
                'expire_date' => $info['expire_date'],
                'sale_no' => GetSaleNoAction::run($client->branch_id),
                'message' => $info['message'],
            ]);
            dump($sale);

            throw_if(! $sale, new AppException('Satış oluşturulamadı.'));

            foreach ($info['services'] as $service) {
                if ($service['type'] == 'service') {
                    ClientService::create([
                        'branch_id' => $client->branch_id,
                        'client_id' => $client->id,
                        'service_id' => $service['service_id'],
                        'sale_id' => $sale->id,
                        'total' => $service['quantity'],
                        'remaining' => $service['quantity'],
                        'status' => SaleStatus::success,
                        'gift' => $service['gift'],
                    ]);
                } else {
                    $package = Package::where('id', $service['package_id'])
                        ->where('active', true)
                        ->with('items')->first();

                    throw_if(! $package, new AppException('Paket bulunamadı.'));

                    foreach ($package->items as $item) {
                        ClientService::create([
                            'branch_id' => $client->branch_id,
                            'client_id' => $client->id,
                            'package_id' => $package->id,
                            'service_id' => $item->service_id,
                            'sale_id' => $sale->id,
                            'status' => SaleStatus::success,
                            'total' => $item->quantity * $service['quantity'],
                            'remaining' => $item->quantity * $service['quantity'],
                            'gift' => $service['gift'],
                        ]);
                    }
                }
            }

            foreach ($info['nakits'] as $pesinat) {
                $sale->transactions()->create([
                    'kasa_id' => $pesinat['kasa_id'],
                    'branch_id' => $client->branch_id,
                    'user_id' => $info['user_id'],
                    'client_id' => $client->id,
                    'date' => $pesinat['date'],
                    'price' => $pesinat['price'],
                    'message' => $sale->sale_no . ' nolu sözleşmeden alınan peşinat',
                    'type' => TransactionType::pesinat,
                ]);
            }


            $offer->status = OfferStatus::success;
            $offer->save();

            \DB::commit();

            return [$sale->id];
        });
    }
}
