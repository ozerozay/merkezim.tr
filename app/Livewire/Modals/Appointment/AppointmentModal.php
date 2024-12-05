<?php

namespace App\Livewire\Modals\Appointment;

use App\Actions\Spotlight\Actions\Appointment\CancelAppointmentAction;
use App\Actions\Spotlight\Actions\Appointment\FinishAppointmentAction;
use App\Actions\Spotlight\Actions\Appointment\ForwardAppointmentAction;
use App\Actions\Spotlight\Actions\Appointment\MerkezdeAppointmentAction;
use App\AppointmentStatus;
use App\Enum\PermissionType;
use App\Models\Appointment;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class AppointmentModal extends SlideOver
{
    use Toast;

    public int|Appointment $appointment;

    public $group = 'info';

    public $messageMerkezde;

    public $merkezdeTeyitliStatus = 'teyitli';

    public $merkezdeTeyitliStatuses = [];

    public $allowMerkezde = [];

    public $allowForward = [];

    public $allowCancel = [];

    public $allowFinish = [];

    public $appointmentClientServices = [];

    public $messageStatus;

    public $finishUser = null;

    public $messageApprove;

    public $forwardUser;

    public $messageForward;

    public $messageCancel;

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment->load('client:id,name,phone', 'serviceRoom:id,name', 'services.service');

        $this->appointmentClientServices = $this->appointment->status == AppointmentStatus::finish
        ? $this->appointment->finish_service_ids
        : $this->appointment->service_ids;

        $this->dispatch('reload-services', $this->appointment->client_id, $this->appointmentClientServices, 'yok');

        $this->merkezdeTeyitliStatuses = [
            ['id' => AppointmentStatus::merkez->name,
                'name' => AppointmentStatus::merkez->label()],
            ['id' => AppointmentStatus::teyitli->name,
                'name' => AppointmentStatus::teyitli->label()],
        ];

        $this->allowMerkezde = [
            AppointmentStatus::teyitli,
            AppointmentStatus::waiting,
            AppointmentStatus::confirmed,
            AppointmentStatus::merkez,
        ];

        $this->allowCancel = [
            AppointmentStatus::cancel,
        ];

        $this->allowForward = [
            AppointmentStatus::waiting,
            AppointmentStatus::confirmed,
            AppointmentStatus::merkez,
            AppointmentStatus::late,
            AppointmentStatus::forwarded,
            AppointmentStatus::teyitli,
        ];

        $this->allowFinish = [
            AppointmentStatus::waiting,
            AppointmentStatus::confirmed,
            AppointmentStatus::merkez,
            AppointmentStatus::late,
            AppointmentStatus::forwarded,
            AppointmentStatus::teyitli,
        ];
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function finish(): void
    {
        $validator = \Validator::make(
            [
                'id' => $this->appointment->id,
                'message' => $this->messageApprove,
                'service_ids' => $this->appointmentClientServices,
                'user_id' => auth()->user()->id,
                'finish_user_id' => $this->finishUser,
                'permission' => PermissionType::page_randevu,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'service_ids' => 'required',
                'user_id' => 'required',
                'finish_user_id' => 'required',
                'permission' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        FinishAppointmentAction::run($validator->validated());

        $this->dispatch('refresh-appointments');
        $this->success('Randevu onaylandı.');
        $this->close();

    }

    public function confirmAppointment(): void
    {
        $validator = \Validator::make(
            [
                'id' => $this->appointment->id,
                'message' => $this->messageMerkezde,
                'status' => $this->merkezdeTeyitliStatus,
                'user_id' => auth()->user()->id,
                'permission' => PermissionType::page_randevu,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'status' => 'required',
                'user_id' => 'required',
                'permission' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        MerkezdeAppointmentAction::run($validator->validated());

        $this->dispatch('refresh-appointments');
        $this->success('Randevu durumu güncellendi.');
        $this->close();
    }

    public function cancelAppointment(): void
    {
        $validator = \Validator::make(
            [
                'id' => $this->appointment->id,
                'message' => $this->messageCancel,
                'user_id' => auth()->user()->id,
                'permission' => PermissionType::page_randevu,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'user_id' => 'required',
                'permission' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CancelAppointmentAction::run($validator->validated());

        $this->dispatch('refresh-appointments');
        $this->success('Randevu iptal edildi.');
        $this->close();
    }

    public function forwardAppointment(): void
    {
        $validator = \Validator::make(
            [
                'id' => $this->appointment->id,
                'message' => $this->messageForward,
                'user_id' => auth()->user()->id,
                'forward_user_id' => $this->forwardUser,
                'permission' => PermissionType::page_randevu,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'user_id' => 'required',
                'forward_user_id' => 'required',
                'permission' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        ForwardAppointmentAction::run($validator->validated());

        $this->dispatch('refresh-appointments');
        $this->success('Randevu yönlendirildi.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.modals.appointment.appointment-modal');
    }
}
