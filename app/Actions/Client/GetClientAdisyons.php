<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\Adisyon;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientAdisyons
{
    use AsAction;

    public function handle($client, $paginate, $order)
    {
        try {
            $query = Adisyon::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->withCount(['adisyonServices']);

            return $paginate ? $query->paginate(10) : $query->get();
        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
