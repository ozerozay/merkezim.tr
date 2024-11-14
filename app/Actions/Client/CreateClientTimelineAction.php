<?php

namespace App\Actions\Client;

use App\Models\ClientTimeline;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateClientTimelineAction
{
    use AsAction;

    public function handle($info): void
    {
        try {
            ClientTimeline::create($info);
        } catch (\Throwable $e) {
        }
    }
}
