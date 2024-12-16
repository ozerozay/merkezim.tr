<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Actions\Spotlight\Actions\Get\GetSaleNoAction;
use App\Exceptions\AppException;
use App\Models\ClientService;
use App\Models\ClientTaksit;
use App\Models\Coupon;
use App\Models\Sale;
use App\Models\User;
use App\Peren;
use App\SaleStatus;
use App\TransactionType;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSaleAction
{
    use AsAction;

    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            $client = User::select('id', 'name', 'branch_id')->where('id', $info['client_id'])->first();

            $sale = Sale::create([
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
                'expire_date' => $info['expire_date'] == 0 ? null : \Carbon\Carbon::now()->addMonths($info['expire_date']),
                'sale_no' => GetSaleNoAction::run($client->branch_id),
                'message' => $info['message'],
            ]);

            throw_if(! $sale, new AppException('Satış oluşturulamadı.'));

            Coupon::where('id', $info['coupon_id'])->decrement('count', 1);

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
                    $package = \App\Models\Package::where('id', $service['package_id'])
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

            foreach ($info['taksits'] as $taksit) {
                ClientTaksit::create([
                    'client_id' => $client->id,
                    'branch_id' => $client->branch_id,
                    'sale_id' => $sale->id,
                    'total' => $taksit['price'],
                    'remaining' => $taksit['price'],
                    'status' => SaleStatus::success,
                    'date' => Peren::parseDateField($taksit['date']),
                ]);
            }

            foreach ($info['nakits'] as $pesinat) {
                $sale->transactions()->create([
                    'kasa_id' => $pesinat['kasa_id'],
                    'branch_id' => $client->branch_id,
                    'user_id' => $info['user_id'],
                    'client_id' => $client->id,
                    'date' => $pesinat['date'],
                    'price' => $pesinat['price'],
                    'message' => $sale->sale_no.' nolu sözleşmeden alınan peşinat',
                    'type' => TransactionType::pesinat,
                ]);
            }

            \DB::commit();

            return [$sale->id];
        }, $approve);
    }
}
