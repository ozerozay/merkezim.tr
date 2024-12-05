<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\AppointmentStatus;
use App\Enum\PermissionType;
use App\Models\Appointment;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class AppointmentQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('appointment', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $appointmentToken, $query) {
            $appointments_list = Appointment::query()
                ->select('id', 'client_id', 'status', 'service_ids', 'date')
                ->where('client_id', $clientToken->getParameter('id'))
                ->with('services.service:id,name')
                ->get();
            $appointments_group = $appointments_list->groupBy('status');
            $results = collect();

            $results->push(SpotlightResult::make()
                ->setTitle('Tümünü görüntüle')
                ->setGroup('actions')
                ->setIcon('check-circle')
                ->setAction('jump_to',
                    ['path' => route('admin.client.profil.index', ['user' => $clientToken->getParameter('id'), 'tab' => 'appointment']),
                    ]));
            foreach ($appointments_group as $status => $appointments) {
                foreach ($appointments as $appointment) {
                    if (AppointmentStatus::active()->contains($appointment->status)) {
                        $results->push(SpotlightResult::make()
                            ->setTitle($appointment->status->label().' - '.$appointment->dateHuman.' - '.$appointment->serviceNames)
                            ->setGroup('appointments_active')
                            //->setAction('get_client_notes_action', ['client' => $note->client_id])
                            ->setIcon('check-circle')
                            ->setAction('dispatch_event',
                                ['name' => 'slide-over.open',
                                    'data' => ['component' => 'modals.appointment.appointment-modal',
                                        'arguments' => [
                                            'appointment' => $appointment->id]],
                                ]));
                    } elseif (AppointmentStatus::deactive()->contains($appointment->status)) {
                        $results->push(SpotlightResult::make()
                            ->setTitle($appointment->status->label().' - '.$appointment->dateHuman.' - '.$appointment->serviceNames)
                            ->setGroup('appointments_cancel')
                            //->setAction('get_client_notes_action', ['client' => $note->client_id])
                            ->setIcon('x-circle')->setAction('dispatch_event',
                                ['name' => 'slide-over.open',
                                    'data' => ['component' => 'modals.appointment.appointment-modal',
                                        'arguments' => [
                                            'appointment' => $appointment->id]],
                                ]));
                    } else {
                        $results->push(SpotlightResult::make()
                            ->setTitle($appointment->status->label().' - '.$appointment->dateHuman.' - '.$appointment->serviceNames)
                            ->setGroup('appointments_finish')
                            //->setAction('get_client_notes_action', ['client' => $note->client_id])
                            ->setIcon('face-smile')->setAction('dispatch_event',
                                ['name' => 'slide-over.open',
                                    'data' => ['component' => 'modals.appointment.appointment-modal',
                                        'arguments' => [
                                            'appointment' => $appointment->id]],
                                ]));
                    }
                }
            }

            $r = collect()->merge($results);

            if (SpotlightCheckPermission::run(PermissionType::action_client_create_appointment)) {
                $app = new Appointment;
                $app->id = 1;
                $app2 = new Appointment;
                $app2->id = 2;
                $app3 = new Appointment;
                $app3->id = 3;
                $actions = collect([
                    SpotlightResult::make()
                        ->setTitle('Randevu Oluştur - Belirli Tarih ve Saat')
                        ->setGroup('appointment_actions')
                        ->setIcon('plus-circle')
                        ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'appointment' => $app, 'appointment_create' => new Appointment]),
                    SpotlightResult::make()
                        ->setTitle('Randevu Oluştur - Tarih Aralığına Göre')
                        ->setGroup('appointment_actions')
                        ->setIcon('plus-circle')
                        ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'appointment' => $app2, 'appointment_create' => new Appointment]),
                    SpotlightResult::make()
                        ->setTitle('Randevu Oluştur - Birden Fazla Tarih Seçerek')
                        ->setGroup('appointment_actions')
                        ->setIcon('plus-circle')
                        ->setTokens(['client' => User::find($clientToken->getParameter('id')), 'appointment' => $app3, 'appointment_create' => new Appointment]),
                ]);
                $r = $r->merge($actions);
            }

            return $r;
        });
    }
}
