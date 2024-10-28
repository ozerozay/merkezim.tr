<?php

/** @noinspection PhpUnreachableStatementInspection */

namespace App\Actions\Offer;

use App\Actions\Client\CreateServiceAction;
use App\Exceptions\AppException;
use App\Models\Offer;
use App\Models\Package;
use App\Models\User;
use App\OfferStatus;
use App\SaleStatus;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class ApproveOfferAction
{
    use AsAction;

    public function handle($offer)
    {
        try {

            DB::beginTransaction();

            $offer = Offer::where('id', $offer)->with('items')->first();

            $client = User::select(['id', 'name', 'branch_id'])->where('id', $offer->client_id)->first();

            throw_if(! $offer, new AppException('Teklif bulunamadı.'));

            foreach ($offer->items as $item) {
                if ($item->itemable_type == 'service') {
                    CreateServiceAction::run([
                        'branch_id' => $client->branch_id,
                        'client_id' => $client->id,
                        'user_id' => $offer->user_id,
                        'service_id' => $item->itemable_id,
                        'sale_id' => null,
                        'total' => $item->quantity,
                        'remaining' => $item->quantity,
                        'status' => SaleStatus::success,
                        'gift' => $item->gift,
                        'message' => $offer->unique_id.' nolu teklif ile oluşturuldu.',
                    ]);
                } elseif ($item->itemable_type == 'package') {
                    $package = Package::find($item->itemable_id);
                    foreach ($package->items as $pi) {
                        CreateServiceAction::run([
                            'branch_id' => $client->branch_id,
                            'client_id' => $client->id,
                            'user_id' => $offer->user_id,
                            'package_id' => $pi->id,
                            'service_id' => $pi->service_id,
                            'sale_id' => null,
                            'total' => $item->quantity * $pi->quantity,
                            'remaining' => $item->quantity * $pi->quantity,
                            'status' => SaleStatus::success,
                            'gift' => $item->gift,
                            'message' => $offer->unique_id.' nolu teklif ile oluşturuldu.',
                        ]);
                    }
                }
            }

            $offer->status = OfferStatus::success;
            $offer->save();

            DB::commit();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
