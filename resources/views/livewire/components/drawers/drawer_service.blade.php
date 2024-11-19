<?php


new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[\Livewire\Attributes\Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public bool $isLoading = false;

    public ?string $messageDelete = null;
    public ?string $messageEdit = null;
    public ?string $messageStatus = null;

    public $service;

    // EDIT
    public $service_remaining;

    public $service_total;

    public $service_sale_id;

    public $service_service_id;

    public $branch_id;

    public $client_id;

    // STATUS
    public $service_status;

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
            $this->service = \App\Models\ClientService::query()
                ->where('id', $this->id)
                ->first();

            $this->service_remaining = $this->service->remaining;
            $this->service_total = $this->service->total;
            $this->service_sale_id = $this->service->sale_id;
            $this->service_service_id = $this->service->service_id;
            $this->branch_id = $this->service->branch_id;
            $this->client_id = $this->service->client_id;
            $this->service_status = $this->service->status->name;

            //dump($this->service_sale_id);

            $this->dispatch('service-dropdown-update-branch', $this->branch_id)
                ->to('components.form.service_dropdown');
            $this->dispatch('reload-sales', $this->client_id)
                ->to('components.client.client_sale_dropdown');

            $this->isLoading = false;
        } catch (\Throwable $e) {
            $this->isOpen = false;
            $this->error('Hizmet bulunamadı.');
        }
    }

    public function delete(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'message' => $this->messageDelete,
        ], [
            'id' => 'required|exists:client_services',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\ClientService\DeleteClientServiceAction::run($validator->validated());


        $this->isOpen = false;
        $this->reset('messageDelete');
        $this->dispatch('refresh-client-services');
        $this->success('Hizmet silindi.');
    }

    public function edit(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'message' => $this->messageEdit,
            'remaining' => $this->service_remaining,
            'total' => $this->service_total,
            'sale_id' => $this->service_sale_id,
            'service_id' => $this->service_service_id,
        ], [
            'id' => 'required|exists:client_services',
            'message' => 'required',
            'remaining' => 'required|int',
            'total' => 'required|int',
            'sale_id' => 'nullable|exists:sale,id',
            'service_id' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\ClientService\EditClientServiceAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-client-services');
        $this->reset('service_remaining', 'service_total', 'service_sale_id', 'service_service_id', 'messageEdit');
        $this->success('Hizmet düzenlendi.');
    }

    public function changeStatus(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'message' => $this->messageStatus,
            'status' => $this->service_status,
            
        ], [
            'id' => 'required|exists:client_services',
            'message' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\ClientService\UpdateClientServiceStatusAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-client-services');
        $this->reset('service_status', 'messageStatus');
        $this->success('Hizmet durumu düzenlendi.');
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
                <x-collapse name="group0">
                    <x-slot:heading>
                        <x-icon name="tabler.toggle-right" label="Durum"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="changeStatus">
                            <livewire:components.form.active_cancel_status_dropdown wire:model="service_status"/>
                            <x-input label="Açıklama" wire:model="messageStatus"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="changeStatus" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Düzenle"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="edit">
                            <livewire:components.client.client_sale_dropdown wire:model="service_sale_id"
                                                                             :client_id="$client_id"/>
                            <livewire:components.form.number_dropdown wire:model="service_remaining" label="Kalan"
                                                                      :includeZero="true"/>
                            <livewire:components.form.number_dropdown wire:model="service_total" label="Toplam"
                                                                      :includeZero="true"/>
                            <livewire:components.form.service_dropdown wire:model="service_service_id"
                                                                       :branch_id="$branch_id"/>
                            <x-input label="Açıklama" wire:model="messageEdit"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="edit" class="btn-primary"/>
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

