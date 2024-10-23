<?php

namespace App\Actions\Offer;

use App\Actions\Helper\CreateOfferCodeAction;
use App\Exceptions\AppException;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Service;
use App\Models\User;
use App\OfferStatus;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateOfferAction
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

            $offer = Offer::create([
                'unique_id' => CreateOfferCodeAction::run(),
                'user_id' => $user->id,
                'client_id' => $client->id,
                'expire_date' => $info['expire_date'],
                'price' => $info['price'],
                'status' => OfferStatus::waiting,
                'message' => $info['message'],
                'month' => $info['month'],
            ]);

            foreach ($info['services'] as $s) {
                dump($s);
                if ($s['type'] == 'service') {
                    $service = Service::find($s['service_id']);
                    $service->offerItem()->create([
                        'offer_id' => $offer->id,
                        'quantity' => $s['quantity'],
                        'gift' => $s['gift'],
                    ]);
                } else {
                    $package = Package::find($s['package_id']);
                    $package->offerItem()->create([
                        'offer_id' => $offer->id,
                        'quantity' => $s['quantity'],
                        'gift' => $s['gift'],
                    ]);
                }
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
