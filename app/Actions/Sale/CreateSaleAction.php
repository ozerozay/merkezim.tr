<?php

namespace App\Actions\Sale;

use App\Actions\Client\CreateServiceAction;
use App\Actions\Client\CreateTaksitAction;
use App\Actions\Helper\CreateSaleUniqueID;
use App\Actions\User\CheckApproveAction;
use App\Exceptions\AppException;
use App\Models\Package;
use App\Models\Sale;
use App\Models\User;
use App\Peren;
use App\SaleStatus;
use App\Traits\StrHelper;
use App\TransactionType;
use Carbon\Carbon;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSaleAction
{
    use AsAction, StrHelper;

    public function handle(array $info)
    {
        try {
            DB::beginTransaction();

            $client = User::find($info['client_id']);

            throw_if(! $client, 'Danışan bulunamadı.');

            $sale = Sale::create([
                'branch_id' => $client->branch_id,
                'unique_id' => CreateSaleUniqueID::run(),
                'client_id' => $client->id,
                'user_id' => $info['user_id'],
                'sale_type_id' => $info['sale_type_id'],
                'date' => $info['sale_date'],
                'status' => SaleStatus::waiting,
                'price' => $info['sale_price'],
                'price_real' => $info['price_real'],
                'staffs' => $info['staff_ids'],
                'expire_date' => $info['expire_date'] == 0 ? null : Carbon::now()->addMonths($info['expire_date']),
                'sale_no' => GetLastSaleNoAction::run($client->branch_id),
                'message' => $info['message'],
            ]);

            throw_if(! $sale, new AppException('Satış oluşturulamadı.'));

            foreach ($info['services'] as $service) {
                if ($service['type'] == 'service') {
                    CreateServiceAction::run([
                        'branch_id' => $client->branch_id,
                        'client_id' => $client->id,
                        'service_id' => $service['service_id'],
                        'sale_id' => $sale->id,
                        'total' => $service['quantity'],
                        'remaining' => $service['quantity'],
                        'status' => SaleStatus::waiting,
                        'gift' => $service['gift'],
                    ]);

                } else {
                    $package = Package::where('id', $service['package_id'])
                        ->where('active', true)
                        ->with('items')->first();

                    throw_if(! $package, new AppException('Paket bulunamadı.'));

                    foreach ($package->items as $item) {
                        CreateServiceAction::run([
                            'branch_id' => $client->branch_id,
                            'client_id' => $client->id,
                            'package_id' => $package->id,
                            'service_id' => $item->service_id,
                            'sale_id' => $sale->id,
                            'status' => SaleStatus::waiting,
                            'total' => $item->quantity * $service['quantity'],
                            'remaining' => $item->quantity * $service['quantity'],
                            'gift' => $service['gift'],
                        ]);
                    }
                }
            }

            foreach ($info['taksits'] as $taksit) {
                CreateTaksitAction::run([
                    'client_id' => $client->id,
                    'branch_id' => $client->branch_id,
                    'sale_id' => $sale->id,
                    'total' => $taksit['price'],
                    'remaining' => $taksit['price'],
                    'date' => Peren::parseDateField($taksit['date']),
                ]);
            }

            foreach ($info['nakits'] as $pesinat) {
                $sale->transactions()->create([
                    'kasa_id' => $pesinat['kasa'],
                    'branch_id' => $client->branch_id,
                    'user_id' => $info['user_id'],
                    'client_id' => $client->id,
                    'date' => $pesinat['date'],
                    'price' => $pesinat['price'],
                    'message' => $sale->sale_no.' nolu sözleşmeden alınan peşinat',
                    'type' => TransactionType::pesinat,
                ]);
            }

            if (CheckApproveAction::run($info['user_id'])) {
                ApproveSaleAction::run($sale->id, $info['user_id']);
            }

            DB::commit();
        } catch (AppException $e) {
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new AppException('Hata oluştu.'.$e->getMessage());
        }
    }
}
