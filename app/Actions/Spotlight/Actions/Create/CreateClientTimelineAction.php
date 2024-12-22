<?php

namespace App\Actions\Spotlight\Actions\Create;

use App\Models\ClientTimeline;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateClientTimelineAction
{
    use AsAction;

    public function handle($info)
    {
        try {
            ClientTimeline::create($info);
        } catch (\Throwable $e) {
        }
    }
}
