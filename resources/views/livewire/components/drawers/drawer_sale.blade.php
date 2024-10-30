<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[\Livewire\Attributes\Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public bool $isLoading = false;

    public ?string $messageDelete;

    public string $group = "group1";

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
            $this->isLoading = false;
        } catch (\Throwable $e) {
        }
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
                    <x-slot:content></x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Durum Düzenle"/>
                    </x-slot:heading>
                    <x-slot:content></x-slot:content>
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

