<?php

use App\Models\ClientTaksit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {

    use \Mary\Traits\Toast;

    #[Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public bool $isLoading = false;

    public ?string $date;

    public ?array $date_config;

    public ?string $message;

    public ?string $messageDelete;

    public function mount(): void
    {
        $this->date_config = \App\Peren::dateConfig();
    }

    #[On('drawer-taksit-update-id')]
    public function updateID($id): void
    {
        $this->id = $id;
        $this->isLoading = true;
        $this->init();
    }

    public function init(): void
    {
        try {
            $this->taksit = ClientTaksit::query()
                ->where('id', $this->id)
                ->first();
            $this->date = $this->taksit->date;
            $this->isLoading = false;
        } catch (\Throwable $e) {
        }
    }

    public function edit(): void
    {
        $validator = Validator::make([
            'date' => $this->date,
            'message' => $this->message,
            'id' => $this->id,
        ], [
            'date' => 'required|date',
            'message' => 'required',
            'id' => 'required|exists:client_taksits'
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Taksit\UpdateTaksitDateAction::run($validator->validated());

        $this->isOpen = false;
        $this->reset('message');
        $this->dispatch('refresh-client-taksits');
        $this->success('Taksit düzenlendi.');

    }

    public function delete(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'message' => $this->messageDelete
        ], [
            'id' => 'required|exists:client_taksits',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Taksit\DeleteTaksitAction::run($validator->validated());

        $this->isOpen = false;
        $this->reset('messageDelete');
        $this->success('Taksit silindi.');
        $this->dispatch('refresh-client-taksits');
    }

    public $group = 'group1';

};

?>
<div>
    <x-drawer wire:model="isOpen" title="Taksit" class="w-full lg:w-1/3"
              separator with-close-button right>
        @if($isLoading)
            <livewire:components.card.loading.loading/>
        @else
            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Tarih Değiştir"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="edit">
                            <x-datepicker label="Tarih" wire:model="date"
                                          icon="o-calendar" :config="$date_config"/>
                            <x-input label="Açıklama" wire:model="message"/>
                            <x-slot:actions>
                                <x-button label="Gönder" class="btn-primary" spinner="edit" type="submit"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="delete">
                            <x-alert title="Emin misiniz ?" description="Taksit silme işlemi geri alınamaz."
                                     icon="o-minus-circle"
                                     class="alert-error"/>
                            <x-input label="Açıklama" wire:model="messageDelete"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" class="btn-error"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            </x-accordion>
        @endif
    </x-drawer>
</div>
