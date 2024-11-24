<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class CreateAppointmentToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('appointment_create', function (SpotlightScopeToken $token, Appointment $appointment) {
            //$appointment->refresh();
            //$token->setParameters(['client' => $appointment->client_id]);
            //$token->setParameters(['id' => $appointment->id]);
            $token->setText('Randevu Oluştur');
        });
    }
}
