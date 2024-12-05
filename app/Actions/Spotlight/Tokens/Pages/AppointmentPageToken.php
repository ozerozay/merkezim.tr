<?php

namespace App\Actions\Spotlight\Tokens\Pages;

use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class AppointmentPageToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('page_appointment', function (SpotlightScopeToken $token, Appointment $appointment) {
            $appointment->refresh();
            $token->setText('Randevular');
        });
    }
}
