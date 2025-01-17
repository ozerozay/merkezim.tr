<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[\Livewire\Attributes\Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public bool $isLoading = false;

    #[On('drawer-offer-update-id')]
    public function updateID($id): void
    {
        $this->id = $id;
        $this->isLoading = true;
        $this->init();
    }

    public function init(): void
    {
        try {
            $this->adisyon = \App\Models\Offer::query()
                ->where('id', $this->id)
                ->first();
            $this->isLoading = false;
        } catch (\Throwable $e) {
        }
    }
};

?>

<div>
    <x-drawer wire:model="isOpen" title="Teklif" class="w-full lg:w-1/3"
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

                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil"/>
                    </x-slot:heading>
                    <x-slot:content>

                    </x-slot:content>
                </x-collapse>
            </x-accordion>
        @endif
    </x-drawer>
</div>

