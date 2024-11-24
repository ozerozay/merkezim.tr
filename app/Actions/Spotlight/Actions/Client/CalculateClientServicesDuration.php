<?php

namespace App\Actions\Spotlight\Actions\Client;

use Lorisleiva\Actions\Concerns\AsAction;

class CalculateClientServicesDuration
{
    use AsAction;

    public function handle($service_ids)
    {
        return (int) \DB::table('client_services')
            ->join('services', 'client_services.service_id', '=', 'services.id')
            ->whereIn('client_services.id', $service_ids)
            ->sum('services.duration');
    }
}
