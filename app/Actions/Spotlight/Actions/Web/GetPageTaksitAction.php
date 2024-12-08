<?php

namespace App\Actions\Spotlight\Actions\Web;

use App\Models\ClientTaksit;
use App\SaleStatus;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetPageTaksitAction
{
    use AsAction;

    public function handle($show_zero)
    {
        try {

            return ClientTaksit::query()
                ->where('client_id', auth()->user()->id)
                ->where('status', SaleStatus::success)
                ->when(! $show_zero, function ($q) {
                    $q->where('remaining', '>', 0);
                })
                ->with('sale:id,unique_id', 'clientTaksitsLocks.service:id,name')
                ->orderBy('date', 'asc')
                ->get();

        } catch (\Throwable $e) {
            throw ToastException::error('Lütfen tekrar deneyin.');
        }
    }
}
