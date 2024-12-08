<?php

namespace App\Actions\Spotlight\Actions\Web;

use App\Models\Offer;
use App\OfferStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetPageOfferAction
{
    use AsAction;

    public function handle()
    {
        try {
            return Offer::query()
                ->where('client_id', auth()->user()->id)
                ->where('status', OfferStatus::success)
                ->latest()
                ->with('items.itemable')
                ->get();
        } catch (\Throwable $e) {
            throw ToastException::error('Lütfen tekrar deneyin.');
        }

    }
}
