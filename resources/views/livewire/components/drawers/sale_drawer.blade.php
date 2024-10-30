<?php

use App\Models\Sale;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Modelable;

new class extends Component {

    use \Mary\Traits\Toast;

    #[Modelable]
    public bool $saleModel = false;

    public ?int $saleID = null;

    public bool $isLoading = false;

    public ?Sale $sale = null;

    #[On('sale-drawer-update-saleID')]
    public function updateSaleID($saleID): void
    {
        $this->saleID = $saleID;
        $this->isLoading = true;
        $this->init();
    }

    public function init(): void
    {
        try {

            $this->sale = Sale::query()
                ->where('id', $this->saleID)
                ->with('clientServices.service', 'clientTaksits', 'transactions', 'staffs')
                ->first();

            $this->isLoading = false;

        } catch (\Throwable $e) {

        }

    }

};

?>
<div>
    <x-drawer wire:model="saleModel" title="Satış İşlemleri" class="w-full lg:w-1/3"
              separator with-close-button right>
        @if($isLoading)
            <livewire:components.card.loading.loading/>
        @else
            <x-accordion wire:model="group">
                <x-collapse name="group1">
                    <x-slot:heading>Bilgilerini Düzenle</x-slot:heading>
                    <x-slot:content>Hello 1</x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>Durumunu Düzenle</x-slot:heading>
                    <x-slot:content>Hello 1</x-slot:content>
                </x-collapse>
            </x-accordion>
        @endif
    </x-drawer>
</div>
