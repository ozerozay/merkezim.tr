<?php

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;

new class extends \Livewire\Volt\Component {
    use \Mary\Traits\Toast;

    #[Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public bool $isLoading = false;

    public ?\App\Models\Appointment $appointment;

    public array $appointmentStatusList = [];

    public $appointmentClientServices = [];

    public $updateStatusStatus = 'waiting';
    public $messageStatus;
    public $finishUser = null;

    public $messageApprove;

    public $client_id;

    public $forwardUser;
    public $messageForward;

    public $messageCancel;

    public $messageMerkezde;
    public $merkezdeTeyitliStatus = 'teyitli';
    public $merkezdeTeyitliStatuses = [];

    public $messageCloseCancel;

    #[On('drawer-appointment-update-id')]
    public function updateID($id): void
    {
        $this->id = $id;
        $this->isLoading = true;
        $this->init();
    }

    public function init(): void
    {
        try {
            $this->appointment = \App\Models\Appointment::query()
                ->where('id', $this->id)
                ->first();
            $this->isLoading = false;

            $this->appointmentClientServices = $this->appointment->status == \App\AppointmentStatus::finish ? $this->appointment->finish_service_ids : $this->appointment->service_ids;
            $this->client_id = $this->appointment->client_id;

            foreach (\App\AppointmentStatus::cases() as $status) {
                if ($status->name == 'teyitli' || $status->name == 'waiting' || $status->name == 'cancel' || $status->name == 'merkez') {
                    $this->appointmentStatusList[] = [
                        'id' => $status->name,
                        'name' => $status->label(),
                    ];
                }
            }

            $this->merkezdeTeyitliStatuses = [['id' => \App\AppointmentStatus::merkez->name, 'name' => \App\AppointmentStatus::merkez->label()], ['id' => \App\AppointmentStatus::teyitli->name, 'name' => \App\AppointmentStatus::teyitli->label()]];

            //dump($this->appointmentStatusList);
        } catch (\Throwable $e) {
        }
    }

    public function approve()
    {
        dump($this->appointmentClientServices);
    }

    public function updateStatus(): void
    {
        $validator = Validator::make(
            [
                'id' => $this->id,
                'message' => $this->messageStatus,
                'status' => $this->updateStatusStatus,
                'user_id' => auth()->user()->id,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'status' => 'required',
                'user_id' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Appointment\UpdateAppointmentStatusAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-appointments');
        $this->reset('messageStatus', 'updateStatusStatus');
        $this->success('Randevu düzenlendi.');
    }

    public function finish(): void
    {
        $validator = Validator::make(
            [
                'id' => $this->id,
                'message' => $this->messageApprove,
                'service_ids' => $this->appointmentClientServices,
                'user_id' => auth()->user()->id,
                'finish_user_id' => $this->finishUser,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'service_ids' => 'required',
                'user_id' => 'required',
                'finish_user_id' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Appointment\FinishAppointmentAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-appointments');
        $this->reset('messageApprove', 'appointmentClientServices');
        $this->success('Randevu tamamlandı.');
    }

    public function forwardAppointment(): void
    {
        $validator = Validator::make(
            [
                'id' => $this->id,
                'message' => $this->messageForward,
                'user_id' => auth()->user()->id,
                'forward_user_id' => $this->forwardUser,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'user_id' => 'required',
                'forward_user_id' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Appointment\ForwardAppointmentAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-appointments');
        $this->reset('messageForward', 'forwardUser');
        $this->success('Randevu yönlendirildi.');
    }

    public function cancelAppointment(): void
    {
        $validator = Validator::make(
            [
                'id' => $this->id,
                'message' => $this->messageCancel,
                'user_id' => auth()->user()->id,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'user_id' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Appointment\CancelAppointmentAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-appointments');
        $this->reset('messageCancel');
        $this->success('Randevu iptal edildi.');
    }

    public function confirmAppointment(): void
    {
        $validator = Validator::make(
            [
                'id' => $this->id,
                'message' => $this->messageMerkezde,
                'status' => $this->merkezdeTeyitliStatus,
                'user_id' => auth()->user()->id,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'status' => 'required',
                'user_id' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Appointment\MerkezdeAppointmentAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-appointments');
        $this->reset('messageMerkezde', 'merkezdeTeyitliStatus');
        $this->success('Randevu durumu güncellendi.');
    }

    public function closeCancelAppointment(): void
    {
        $validator = Validator::make(
            [
                'id' => $this->id,
                'message' => $this->messageCloseCancel,
                'status' => \App\AppointmentStatus::cancel,
                'user_id' => auth()->user()->id,
            ],
            [
                'id' => 'required',
                'message' => 'required',
                'status' => 'required',
                'user_id' => 'required',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Appointment\CancelAppointmentAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-appointments');
        $this->reset('messageCloseCancel');
        $this->success('Randevu kapatılması iptal edildi.');
    }

    public $group;
};

?>
<div>
    <x-drawer wire:model="isOpen" subtitle="{{ $this->appointment->client->phone ?? '' }}"
        title="{{ $this->appointment->client->name ?? 'KAPALI' }}" class="w-full lg:w-1/3" separator with-close-button
        right>
        @if ($isLoading)
            <livewire:components.card.loading.loading />
        @else
            @if ($appointment)
                @if ($appointment->type == \App\AppointmentType::appointment)
                    <x-accordion wire:model="group" separator class="bg-base-200">
                        @if (
                            !(
                                $appointment->status == \App\AppointmentStatus::finish ||
                                $appointment->status == \App\AppointmentStatus::cancel ||
                                $appointment->status == \App\AppointmentStatus::rejected
                            ))
                            <x-collapse name="group1">
                                <x-slot:heading>
                                    <x-icon name="o-check" label="Tamamla" />
                                </x-slot:heading>
                                <x-slot:content>
                                    <x-form wire:submit="finish">
                                        <livewire:components.client.client_service_multi_dropdown :client_id="$client_id"
                                            label="Hizmetler" wire:model="appointmentClientServices" />
                                        <livewire:components.form.staff_dropdown wire:model="finishUser" />
                                        <x-input label="Açıklama" wire:model="messageApprove" />
                                        <x-slot:actions>
                                            <x-button label="Gönder" type="submit" spinner="finish"
                                                class="btn-primary" />
                                        </x-slot:actions>
                                    </x-form>
                                </x-slot:content>
                            </x-collapse>
                        @endif
                        <x-collapse name="forward">
                            <x-slot:heading>
                                <x-icon name="tabler.corner-down-right-double" label="Yönlendir" />
                            </x-slot:heading>
                            <x-slot:content>
                                <x-form wire:submit="forwardAppointment">
                                    <livewire:components.form.staff_dropdown wire:model="forwardUser" />
                                    <x-input label="Açıklama" wire:model="messageForward" />
                                    <x-slot:actions>
                                        <x-button label="Gönder" type="submit" spinner="forwardAppointment"
                                            class="btn-primary" />
                                    </x-slot:actions>
                                </x-form>
                            </x-slot:content>
                        </x-collapse>
                        <x-collapse name="merkezde">
                            <x-slot:heading>
                                <x-icon name="tabler.checks" label="Merkezde - Teyitli" />
                            </x-slot:heading>
                            <x-slot:content>
                                <x-form wire:submit="confirmAppointment">
                                    <x-select wire:model="merkezdeTeyitliStatus" :options="$merkezdeTeyitliStatuses" />
                                    <x-input label="Açıklama" wire:model="messageMerkezde" />
                                    <x-slot:actions>
                                        <x-button label="Gönder" type="submit" spinner="confirmAppointment"
                                            class="btn-primary" />
                                    </x-slot:actions>
                                </x-form>
                            </x-slot:content>
                        </x-collapse>
                        <x-collapse name="cancel">
                            <x-slot:heading>
                                <x-icon name="tabler.x" label="İptal" />
                            </x-slot:heading>
                            <x-slot:content>
                                <x-form wire:submit="cancelAppointment">
                                    <x-alert title="Emin misiniz ?"
                                        description="İptal ettiğinizde seanslar geri yüklenir ve bu işlem geri alınamaz."
                                        icon="o-minus-circle" class="alert-error" />
                                    <x-input label="Açıklama" wire:model="messageCancel" />
                                    <x-slot:actions>
                                        <x-button label="Gönder" type="submit" spinner="cancelAppointment"
                                            class="btn-primary" />
                                    </x-slot:actions>
                                </x-form>
                            </x-slot:content>
                        </x-collapse>
                        <x-collapse name="past">
                            <x-slot:heading>
                                <x-icon name="tabler.history" label="Geçmiş" />
                            </x-slot:heading>
                            <x-slot:content>
                                @if ($appointment)
                                    @foreach ($appointment->appointmentStatuses as $status)
                                        <x-list-item :item="$status" no-separator no-hover>
                                            <x-slot:actions>
                                                <x-badge value="{{ $status->status->label() }}"
                                                    class="badge-{{ $status->status->color() }}" />
                                            </x-slot:actions>
                                            <x-slot:value>
                                                {{ $status->user->name }}
                                            </x-slot:value>
                                            <x-slot:sub-value>
                                                {{ $status->dateHuman }}
                                            </x-slot:sub-value>
                                        </x-list-item>
                                        {{ $status->message }}
                                    @endforeach
                                @endif
                            </x-slot:content>
                        </x-collapse>
                    </x-accordion>
                @elseif($appointment->type == \App\AppointmentType::close && $appointment->status != \App\AppointmentStatus::cancel)
                    <x-collapse name="cancel">
                        <x-slot:heading>
                            <x-icon name="tabler.x" label="İptal" />
                        </x-slot:heading>
                        <x-slot:content>
                            <x-form wire:submit="closeCancelAppointment">

                                <x-input label="Açıklama" wire:model="messageCloseCancel" />
                                <x-slot:actions>
                                    <x-button label="Gönder" type="submit" spinner="closeCancelAppointment"
                                        class="btn-primary" />
                                </x-slot:actions>
                            </x-form>
                        </x-slot:content>
                    </x-collapse>
                @endif
            @endif
        @endif
    </x-drawer>
</div>
