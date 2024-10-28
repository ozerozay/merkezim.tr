<?php

namespace App\Actions\Offer;

use App\Exceptions\AppException;
use App\Models\Offer;
use App\OfferStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class CancelOfferAction
{
    use AsAction;

    public function handle($offer)
    {
        try {

            $offer = Offer::find($offer);

            $offer->update([
                'status' => OfferStatus::cancel,
            ]);

            $offer->clientServices()->delete();

            $offer->transactions()->delete();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
