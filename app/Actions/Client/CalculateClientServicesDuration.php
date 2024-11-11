<?php

namespace App\Actions\Client;

use App\Models\ClientService;
use Lorisleiva\Actions\Concerns\AsAction;

class CalculateClientServicesDuration
{
    use AsAction;

    public function handle($info)
    {
        try {
            return ClientService::query()
                ->select('id', 'service_id')
                ->whereIn('id', $info)
                ->withSum('service', 'duration')
                ->get()->sum('service.duration') ?? 0;
        } catch (\Throwable $e) {
            return 0;
        }

    }
}
