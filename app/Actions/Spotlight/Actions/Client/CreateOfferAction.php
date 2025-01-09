<?php

namespace App\Actions\Spotlight\Actions\Client;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Service;
use App\OfferStatus;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CreateOfferAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info, $approve = false)
    {
        return Peren::runDatabaseTransactionApprove($info, function () use ($info) {

            $offer = Offer::create([
                'unique_id' => CreateUniqueID::run('offer'),
                'user_id' => $info['user_id'],
                'client_id' => $info['client_id'],
                'expires_date' => $info['expire_date'],
                'price' => $info['price'],
                'status' => OfferStatus::waiting,
                'message' => $info['message'],
                'month' => $info['month'],
            ]);

            foreach ($info['services'] as $s) {
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

            \DB::commit();

            return [$offer->id];
        }, $approve);
    }
}
