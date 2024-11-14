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

    public $config_sale_date;

    public $sale_staffs = [];
    public $sale_type;
    public $sale_date;
    public $expire_date;

    public $sale_status;
    public $freeze_date;

    public string $group = "group1";

    public function mount(): void
    {
        $this->config_sale_date = \App\Peren::dateConfig();

    }

    #[\Livewire\Attributes\On('drawer-sale-update-id')]
    public function updateID($id): void
    {
        $this->id = $id;
        $this->isLoading = true;
        $this->init();
    }

    public function init(): void
    {
        try {
            $this->sale = \App\Models\Sale::query()
                ->where('id', $this->id)
                ->first();

            $this->sale_type = $this->sale->sale_type_id;
            $this->sale_date = $this->sale->date;
            $this->sale_staffs = $this->sale->staffs;
            $this->expire_date = $this->sale->expire_date;

            $this->sale_status = $this->sale->status;
            $this->isLoading = false;
        } catch (\Throwable $e) {
        }
    }

    public function changeStatus(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'message' => $this->messageStatus,
            'status' => $this->sale_status,
            'freeze_date' => $this->freeze_date,
        ], [
            'id' => 'required',
            'message' => 'required',
            'status' => 'required',
            'freeze_date' => 'required_if:status,freeze',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Sale\UpdateClientSaleStatus::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-client-sales');
        $this->reset('sale_status', 'freeze_date', 'messageStatus');
        $this->success('Satış durumu düzenlendi.');
    }

    public function edit(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'message' => $this->messageEdit,
            'sale_type_id' => $this->sale_type,
            'expire_date' => $this->expire_date,
            'date' => $this->sale_date,
            'staffs' => $this->sale_staffs,
        ], [
            'id' => 'required',
            'message' => 'required',
            'sale_type_id' => 'required',
            'expire_date' => 'nullable|after:today',
            'date' => 'required',
            'staffs' => 'nullable',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Sale\EditClientSaleAction::run($validator->validated());

        $this->isOpen = false;
        $this->dispatch('refresh-client-sales');
        $this->reset('messageEdit', 'sale_type', 'expire_date', 'sale_date', 'sale_staffs');
        $this->success('Hizmet düzenlendi.');
    }

    public function delete(): void
    {
        $validator = Validator::make([
            'id' => $this->id,
            'message' => $this->messageDelete,
        ], [
            'id' => 'required|exists:sale',
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Actions\Sale\DeleteClientSale::run($validator->validated());

        $this->isOpen = false;
        $this->reset('messageDelete');
        $this->dispatch('refresh-client-sales');
        $this->success('Satış silindi.');
    }


};

?>
<div>
    <x-drawer wire:model="isOpen" title="Satış" class="w-full lg:w-1/3"
              separator with-close-button right>
        @if($isLoading)
            <livewire:components.card.loading.loading/>
        @else
            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Düzenle"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="edit">
                            <x-datepicker label="Satış Tarihi" wire:model="sale_date" icon="o-calendar"
                                          :config="$config_sale_date"/>
                            <x-datepicker label="Son Kullanım Tarihi" wire:model="expire_date" icon="o-calendar"
                                          :config="$config_sale_date"/>
                            <livewire:components.form.staff_multi_dropdown wire:model="sale_staffs"/>
                            <livewire:components.form.sale_type_dropdown wire:model="sale_type"/>
                            <x-input label="Açıklama" wire:model="messageEdit"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="edit" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Durum Düzenle"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="changeStatus">
                            <livewire:components.form.active_cancel_status_dropdown
                                wire:model.live="sale_status"
                                :expireFreeze="true"/>
                            <x-datepicker label="Ne zaman açılsın ? - Dondurmak için doldurun." wire:model="freeze_date"
                                          icon="o-calendar"
                                          :config="$config_sale_date"/>
                            <x-input label="Açıklama" wire:model="messageStatus"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="changeStatus" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group3">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="delete">
                            <x-alert title="Emin misiniz ?"
                                     description="Satışı silmeniz durumunda ona bağlı kasa işlemleri, hizmetler ve taksitlerde silinir."
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

