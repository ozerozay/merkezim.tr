<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[\Livewire\Attributes\Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public bool $isLoading = false;

    public ?\App\Models\Talep $talep = null;

    // EDİT
    public $name;
    public $phone;
    public $type;
    public $message;

    // PROCESS
    public $messageProcess;
    public $statusProcess = \App\TalepStatus::cevapsiz->name;

    // RANDEVU
    public $messageProcessRandevu;
    public $statusProcessRandevu = \App\TalepStatus::randevu->name;
    public array $date_config = [];
    public $randevuDate;

    public $talepProcessList = [];

    public $talepProcessRandevuList = [];

    #[\Livewire\Attributes\On('drawer-talep-update-id')]
    public function updateID($id): void
    {
        $this->id = $id;
        $this->isLoading = true;
        $this->group = 'group1';
        $this->init();
    }

    public function init(): void
    {
        try {
            $this->talep = \App\Models\Talep::query()
                ->where('id', $this->id)
                ->with('talepProcesses.user:id,name')
                ->first();
            $this->name = $this->talep->name;
            $this->phone = $this->talep->phone;
            $this->type = $this->talep->type;
            $this->message = $this->talep->message;
            $this->isLoading = false;
            $this->date_config = \App\Peren::dateConfig(min: \Carbon\Carbon::now()->format('Y-m-d'), enableTime: true);
            $this->randevuDate = now();

            $this->talepProcessList = [];
            $this->talepProcessRandevuList = [];

            foreach (\App\TalepStatus::cases() as $case) {
                if ($case->name != 'bekleniyor' && $case->name != 'sonra' && $case->name != 'randevu') {
                    $this->talepProcessList[] = [
                        'id' => $case->name,
                        'name' => $case->label()
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
        } catch (\Throwable $e) {

        }
    }

    public function addProcess(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'status' => $this->statusProcess,
            'message' => $this->messageProcess,
            'user_id' => auth()->user()->id,
            'date' => date('Y-m-d H:i:s'),
            'talep_id' => $this->id,
        ], [
            'id' => 'required|exists:taleps,id',
            'status' => 'required',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
            'date' => 'required',
            'talep_id' => 'required|exists:taleps,id'
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Talep\CreateTalepProcessAction::run($validator->validated());

        $this->dispatch('refresh-talepss');
        $this->reset('messageProcess');
        $this->isOpen = false;
        $this->success('İşlem eklendi.');
    }

    public function addAppointment(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'status' => $this->statusProcessRandevu,
            'message' => $this->messageProcessRandevu,
            'user_id' => auth()->user()->id,
            'date' => $this->randevuDate,
            'talep_id' => $this->id,
        ], [
            'id' => 'required|exists:taleps,id',
            'status' => 'required',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
            'date' => 'required',
            'talep_id' => 'required|exists:taleps,id'
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Talep\CreateTalepProcessAction::run($validator->validated());

        $this->dispatch('refresh-talepss');
        $this->reset('messageProcessRandevu');
        $this->isOpen = false;
        $this->success('İşlem eklendi.');
    }

    public function edit(): void
    {
        $validator = Validator::make(
            [
                'id' => $this->id,
                'message' => $this->message,
                'phone' => $this->phone,
                'type' => $this->type,
                'name' => $this->name,
            ],
            [
                'id' => 'required|exists:taleps,id',
                'message' => 'required',
                'phone' => ['required', \Illuminate\Validation\Rule::unique('taleps', 'phone')->ignore($this->id)],
                'type' => 'required',
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Talep\EditTalepAction::run($validator->validated());

        $this->reset('message');
        $this->dispatch('refresh-talepss');
        $this->success('Talep düzenlendi.');
    }

    public string $group = 'group1';

};

?>
<div>
    <x-drawer wire:model="isOpen" title="Talep" class="w-full lg:w-1/3"
              separator with-close-button right>
        @if($isLoading)
            <livewire:components.card.loading.loading/>
        @else
            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-plus" label="İşlem"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="addProcess">
                            <x-select label="İşlem" wire:model="statusProcess"
                                      :options="$talepProcessList"/>
                            <x-input label="Açıklama" wire:model="messageProcess"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="addProcess" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>

                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group4">
                    <x-slot:heading>
                        <x-icon name="o-calendar" label="Randevu - Sonra Ara"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="addAppointment">
                            <x-select label="İşlem" wire:model="statusProcessRandevu"
                                      :options="$talepProcessRandevuList"/>
                            <x-datepicker label="Tarih" wire:model="randevuDate"
                                          icon="o-calendar" :config="$date_config"/>
                            <x-input label="Açıklama" wire:model="messageProcessRandevu"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="addAppointment" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Düzenle"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="edit">
                            <x-input wire:model="name" label="Ad"/>
                            <livewire:components.form.phone wire:model="phone"/>
                            <livewire:components.form.talep_type_dropdown wire:model="type"/>
                            <x-input label="Açıklama" wire:model="message"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="edit" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group3">
                    <x-slot:heading>
                        <x-icon name="tabler.history" label="Geçmiş"/>
                    </x-slot:heading>
                    <x-slot:content>
                        @if ($talep)
                            @foreach($talep->talepProcesses as $process)
                                <x-list-item :item="$process" no-separator no-hover>
                                    <x-slot:actions>
                                        <x-badge value="{{ $process->status->label()  }}"
                                                 class="badge-{{ $process->status->color()  }}"/>
                                    </x-slot:actions>
                                    <x-slot:value>
                                        {{ $process->user->name  }}
                                    </x-slot:value>
                                    <x-slot:sub-value>
                                        {{ $process->dateHuman  }}
                                    </x-slot:sub-value>
                                </x-list-item>
                                {{ $process->message  }}
                            @endforeach
                        @endif
                    </x-slot:content>
                </x-collapse>
            </x-accordion>
        @endif
    </x-drawer>
</div>


