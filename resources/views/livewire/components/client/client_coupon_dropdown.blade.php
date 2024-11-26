<?php

use App\Actions\Client\GetClientActiveSale;
use App\Actions\Spotlight\Actions\Client\Get\GetClientCoupons;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    #[Modelable]
    public $coupon_id = 0;

    public $client_id;

    public $minOrder = 0;

    public Collection $coupons;

    public $label = 'Aktif Kuponlar';

    public function mount(): void
    {
        $this->getCoupons($this->client_id, $this->minOrder);
    }

    public function getCoupons($client, $minOrder): void
    {
        $coupons = GetClientCoupons::run($client, $minOrder);
        $this->coupons = $coupons->isEmpty() ? collect([]) : $coupons;
    }
};

?>
<div wire:key="csdd-{{Str::random(20)}}">
    <x-choices-offline
        wire:key="csd-{{Str::random(20)}}"
        wire:model="coupon_id"
        :options="$coupons"
        option-sub-label="discount_amount"
        option-label="code"
        :label="$label"
        icon="o-magnifying-glass"
        no-result-text="Aktif kuponu bulunmuyor."
        single
        searchable/>
</div>


