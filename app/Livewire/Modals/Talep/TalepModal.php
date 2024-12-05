<?php

namespace App\Livewire\Modals\Talep;

use App\Actions\Spotlight\Actions\Talep\CreateTalepProcessAction;
use App\Actions\Spotlight\Actions\Talep\EditTalepAction;
use App\Enum\PermissionType;
use App\Models\Talep;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class TalepModal extends SlideOver
{
    use Toast;

    public int|Talep $talep;

    public $group = 'info';

    public $name;

    public $phone;

    public $type;

    public $message;

    public $messageProcess;

    public $talepProcessList = [];

    public $talepProcessRandevuList = [];

    public $messageProcessRandevu;

    public $statusProcessRandevu = \App\TalepStatus::randevu->name;

    public $randevuDate;

    public $statusProcess;

    public function mount(Talep $talep)
    {
        $this->talep = $talep;

        $this->name = $this->talep->name;
        $this->phone = $this->talep->phone;
        $this->type = $this->talep->type;
        $this->message = $this->talep->message;

        $this->talepProcessList = [];
        $this->talepProcessRandevuList = [];

        $this->randevuDate = date('Y-m-d H:i');

        $this->statusProcess = \App\TalepStatus::cevapsiz->name;

        foreach (\App\TalepStatus::cases() as $case) {
            if ($case->name != 'bekleniyor' && $case->name != 'sonra' && $case->name != 'randevu') {
                $this->talepProcessList[] = [
                    'id' => $case->name,
                    'name' => $case->label(),
                ];
            }
        }

        $this->talepProcessRandevuList = [
            [
                'id' => \App\TalepStatus::randevu->name,
                'name' => \App\TalepStatus::randevu->label(),
            ],
            [
                'id' => \App\TalepStatus::sonra->name,
                'name' => \App\TalepStatus::sonra->label(),
            ],
        ];
    }

    public function edit(): void
    {
        $validator = \Validator::make(
            [
                'id' => $this->talep->id,
                'message' => $this->message,
                'phone' => $this->phone,
                'type' => $this->type,
                'name' => $this->name,
                'user_id' => auth()->user()->id,
                'permission' => PermissionType::page_talep,
            ],
            [
                'id' => 'required|exists:taleps,id',
                'message' => 'required',
                'phone' => ['required', \Illuminate\Validation\Rule::unique('taleps', 'phone')->ignore($this->talep->id)],
                'type' => 'required',
                'user_id' => 'required',
                'name' => 'required',
                'permission' => 'required',
            ]
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        EditTalepAction::run($validator->validated());

        $this->dispatch('refresh-taleps');
        $this->success('Talep düzenlendi.');
        $this->close();
    }

    public function addProcess(): void
    {
        $validator = \Validator::make([
            'id' => $this->talep->id,
            'status' => $this->statusProcess,
            'message' => $this->messageProcess,
            'user_id' => auth()->user()->id,
            'date' => date('Y-m-d H:i:s'),
            'talep_id' => $this->talep->id,
            'permission' => PermissionType::page_talep,
        ], [
            'id' => 'required|exists:taleps,id',
            'status' => 'required',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
            'date' => 'required',
            'talep_id' => 'required|exists:taleps,id',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateTalepProcessAction::run($validator->validated());

        $this->dispatch('refresh-taleps');
        $this->success('İşlem eklendi.');
        $this->close();
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

    public function render()
    {
        return view('livewire.spotlight.modals.talep.talep-modal');
    }
}
