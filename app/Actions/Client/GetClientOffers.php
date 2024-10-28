<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\Offer;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientOffers
{
    use AsAction;

    public function handle($client, $paginate, $order)
    {
        try {

            $query = Offer::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->withCount('items')
                ->with('user:id,name', 'items');

            return $paginate ? $query->paginate(10) : $query->get();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
