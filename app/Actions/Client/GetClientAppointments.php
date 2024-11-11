<?php

namespace App\Actions\Client;

use App\Exceptions\AppException;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientAppointments
{
    use AsAction;

    public function handle($client, $paginate, $order)
    {
        try {

            $query = Appointment::query()
                ->where('client_id', $client)
                ->orderBy(...array_values($order))
                ->with('client:id,name', 'serviceRoom:id,name', 'serviceCategory:id,name');

            return $paginate ? $query->paginate(10) : $query->get();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
