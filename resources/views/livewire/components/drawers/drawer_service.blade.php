<?php


new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[\Livewire\Attributes\Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public bool $isLoading = false;

    public ?string $messageDelete;

    #[\Livewire\Attributes\On('drawer-service-update-id')]
    public function updateID($id): void
    {
        $this->id = $id;
        $this->isLoading = true;
        $this->init();
    }

    public function init(): void
    {
        try {
            $this->taksit = \App\Models\ClientService::query()
                ->where('id', $this->id)
                ->first();
            $this->isLoading = false;
        } catch (\Throwable $e) {
        }
    }

    public function delete(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
        ], [
            'id' => 'required|exists:client_services'
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\ClientService\DeleteClientServiceAction::run($validator->validated());

        $this->isOpen = false;
        $this->reset('messageDelete');
        $this->success('Hizmet silindi.');
    }
};
?>
<div>
    <x-drawer wire:model="isOpen" title="Hizmet" class="w-full lg:w-1/3"
              separator with-close-button right>
        @if($isLoading)
            <livewire:components.card.loading.loading/>
        @else
            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="delete">
                            <x-alert title="Emin misiniz ?" description="Hizmet silme işlemi geri alınamaz."
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

