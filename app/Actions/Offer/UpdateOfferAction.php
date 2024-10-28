<?php

namespace App\Actions\Offer;

use App\Exceptions\AppException;
use App\Models\Offer;
use App\OfferStatus;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class UpdateOfferAction
{
    use AsAction;

    public function handle($info)
    {
        try {

            $offer = Offer::find($info['offer_id']);

            $offer->update([
                'price' => $info['price'],
                'message' => $info['message'],
                'expire_date' => $info['expire_date'],
                'month' => $info['month'],
                'status' => (Carbon::parse($info['expire_date'])->gt(Carbon::now()) ? OfferStatus::waiting : $offer->status),
            ]);

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
