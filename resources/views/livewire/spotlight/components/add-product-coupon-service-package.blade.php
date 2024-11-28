<?php

use App\Models\User;

new class extends Livewire\Volt\Component {
    use Mary\Traits\Toast;

    public $price = 0.0;

    public int|User $client;

    public $service_collection;

    public bool $checkPaymentTotalPrice = true;

    public $couponPrice = 0;

    public $actives = [];

    public ?Illuminate\Support\Collection $selected_services;

    public ?Illuminate\Support\Collection $selected_payments;

    public $coupon = null;

    public function mount(User $client): void
    {
        $this->client = $client;
    }

    #[Livewire\Attributes\On('price-changed')]
    public function priceChanged($price): void
    {
        $this->price = $price;
    }

    #[Livewire\Attributes\On('modal-payment-added')]
    public function addPayment($payment): void
    {
        $payment['price'] = (float) $payment['price'];

        if ($this->checkPaymentTotalPrice) {
            $remaining = $this->remainingPayment();

            if ($remaining == 0.0) {
                $this->error('Yapılandırılacak tutar 0');

                return;
            }

            if ($payment['price'] > $remaining) {
                $payment['price'] = $remaining;
                $this->warning('Tutar ' . $payment['price'] . ' olarak güncellendi.');
            }
        }

        $kasa = App\Models\Kasa::where('id', $payment['kasa_id'])->first();
        $lastId = $this->selected_payments->last() != null ? $this->selected_payments->last()['id'] + 1 : 1;
        $this->selected_payments->push([
            'id' => $lastId,
            'kasa_id' => $kasa->id,
            'kasa_name' => $kasa->name,
            'price' => $payment['price'],
            'date' => $payment['date'],
        ]);

        $this->dispatchSelectedPayments($this->selected_services);
    }

    #[Livewire\Attributes\On('modal-service-added')]
    public function addService($service): void
    {
        $services = App\Models\Service::whereIn('id', $service['service_ids'])->get();

        foreach ($services as $s) {
            $lastId = $this->selected_services->last() != null ? $this->selected_services->last()['id'] + 1 : 1;
            $this->selected_services->push([
                'id' => $lastId,
                'service_id' => $s->id,
                'type' => 'service',
                'name' => $s->name,
                'gift' => $service['gift'],
                'quantity' => $service['quantity'],
                'price' => $s->price * $service['quantity'],
            ]);
        }

        $this->deleteCoupon();
        //dump($this->selected_services);
        $this->dispatchSelectedServices($this->selected_services);
    }

    #[Livewire\Attributes\On('modal-product-added')]
    public function addProduct($product): void
    {
        $products = App\Models\Product::whereIn('id', $product['product_ids'])->get();

        foreach ($products as $p) {
            $lastId = $this->selected_services->last() != null ? $this->selected_services->last()['id'] + 1 : 1;
            $this->selected_services->push([
                'id' => $lastId,
                'product_id' => $p->id,
                'type' => 'product',
                'name' => $p->name,
                'gift' => $product['gift'],
                'quantity' => $product['quantity'],
                'price' => $p->price * $product['quantity'],
            ]);
        }
        $this->deleteCoupon();

        $this->dispatchSelectedServices($this->selected_services);
    }

    #[Livewire\Attributes\On('modal-package-added')]
    public function addPackage($info): void
    {
        $package = App\Models\Package::where('id', $info['package_ids'])->first();

        if ($package) {
            $lastId = $this->selected_services->last() != null ? $this->selected_services->last()['id'] + 1 : 1;

            $this->selected_services->push([
                'id' => $lastId,
                'package_id' => $package->id,
                'type' => 'package',
                'gift' => $info['gift'],
                'name' => $package->name,
                'quantity' => $info['quantity'],
                'price' => $package->price * $info['quantity'],
            ]);
        }

        $this->deleteCoupon();

        $this->dispatchSelectedServices($this->selected_services);
    }

    #[Livewire\Attributes\On('modal-coupon-added')]
    public function addCoupon($info): void
    {
        $coupon = App\Models\Coupon::where('id', $info['coupon_id'])->first();

        //dump($coupon);
        $this->coupon = $coupon;

        $this->couponPrice = $coupon->discount_type ? ($this->totalPrice() * $coupon->discount_amount) / 100 : $coupon->discount_amount;

        $this->dispatch('selected-coupon-changed', $coupon, $this->couponPrice);
    }

    public function deleteItem($id, $type): void
    {
        $this->selected_services = $this->selected_services->reject(function ($item) use ($id, $type) {
            return $item['id'] == $id && $item['type'] == $type;
        });

        $this->deleteCoupon();
        $this->dispatchSelectedServices($this->selected_services);
    }

    public function deletePayment($id): void
    {
        $this->selected_payments = $this->selected_payments->reject(function ($item) use ($id) {
            return $item['id'] == $id;
        });

        $this->dispatchSelectedPayments($this->selected_payments);
    }

    public function deleteCoupon(): void
    {
        $this->coupon = null;
        $this->couponPrice = null;
        $this->dispatch('selected-coupon-changed', null, 0);
    }

    public function dispatchSelectedPayments($selected_payments): void
    {
        $this->dispatch('selected-payments-changed', $this->selected_payments);
    }

    public function dispatchSelectedServices($selected_services): void
    {
        $this->dispatch('selected-services-changed', $this->selected_services);
    }

    #[Livewire\Attributes\Computed]
    public function totalPrice(): mixed
    {
        if ($this->price > 0) {
            return $this->price - $this->couponPrice;
        }

        $totalPrice = $this->selected_services->where('gift', false)->sum('price') - $this->couponPrice;

        return $totalPrice < 0 ? 0 : $totalPrice;
    }

    #[Livewire\Attributes\Computed]
    public function totalPriceTotal(): mixed
    {
        if ($this->price > 0) {
            return $this->price;
        }

        return $this->selected_services->where('gift', false)->sum('price');
    }

    #[Livewire\Attributes\Computed]
    public function totalPayment(): mixed
    {
        return $this->selected_payments->sum('price');
    }

    #[Livewire\Attributes\Computed]
    public function remainingPayment(): float|int
    {
        return $this->totalPrice() - $this->totalPayment();
    }

    #[Livewire\Attributes\Computed]
    public function totalGift(): mixed
    {
        return $this->selected_services->where('gift', true)->sum('price');
    }
};
?>
<div>
    <x-dropdown label="Ekle" class="btn-outline btn-full" no-x-anchor top>
        <x-slot:trigger>
            <x-button icon="o-plus" label="Ekle" class="btn-outline btn-wide" />
        </x-slot:trigger>
        @if (in_array('service', $actives))
            <x-menu-item icon="o-plus-circle" title="Hizmet"
                wire:click="$dispatch('modal.open', {component: 'modals.select-service-modal', arguments: {'client': {{ $client->id }}}})" />
        @endif
        @if (in_array('package', $actives))
            <x-menu-item icon="o-plus-circle" title="Paket"
                wire:click="$dispatch('modal.open', {component: 'modals.select-package-modal', arguments: {'client': {{ $client->id }}}})" />
        @endif

        @if (in_array('product', $actives))
            <x-menu-separator />
            <x-menu-item icon="o-plus-circle" title="Ürün"
                wire:click="$dispatch('modal.open', {component: 'modals.select-product-modal', arguments: {'client': {{ $client->id }}}})" />
        @endif

        <x-menu-separator />
        @if ($this->totalPrice() > 0)
            @if (in_array('coupon', $actives))
                @if (!$coupon)
                    <x-menu-item icon="o-plus-circle" title="Kupon"
                        wire:click="$dispatch('modal.open', {component: 'modals.select-coupon-modal', arguments: {'client': {{ $client->id }}}})" />
                @endif
            @endif
            @if (in_array('payment', $actives))
                <x-menu-item icon="o-plus-circle" title="Peşinat"
                    wire:click="$dispatch('modal.open', {component: 'modals.select-payment-modal', arguments: {'client': {{ $client->id }}}})" />
            @endif
        @else
            <x-menu-item icon="o-plus-circle" title="Peşinat ve Kupon eklemek için hizmet ekleyin." />
        @endif

    </x-dropdown>
    @foreach ($selected_services as $service)
        <x-list-item :item="$service" no-separator no-hover>
            @if ($service['gift'])
                <x-slot:avatar>
                    <x-badge value="H" class="badge-primary" />
                </x-slot:avatar>
            @endif
            <x-slot:value>
                {{ $service['name'] }}
            </x-slot:value>
            <x-slot:sub-value>
                {{ $service['quantity'] }} seans - @price($service['price'])
            </x-slot:sub-value>
            <x-slot:actions>
                <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                    wire:click="deleteItem({{ $service['id'] }}, '{{ $service['type'] }}')" spinner />
            </x-slot:actions>
        </x-list-item>
    @endforeach
    <x-hr />
    @foreach ($selected_payments as $payment)
        <x-list-item :item="$payment" no-separator no-hover>
            <x-slot:value>
                {{ $payment['kasa_name'] }}
            </x-slot:value>
            <x-slot:sub-value>
                @price($payment['price'])
            </x-slot:sub-value>
            <x-slot:actions>
                <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                    wire:click="deletePayment({{ $payment['id'] }})" spinner />
            </x-slot:actions>
        </x-list-item>
    @endforeach
    <x-hr />
    @if ($coupon)
        <x-list-item :item="$coupon" no-separator no-hover>
            <x-slot:value>
                {{ $coupon->code }}
            </x-slot:value>
            <x-slot:sub-value>
                @price($couponPrice)
            </x-slot:sub-value>
            <x-slot:actions>
                <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?" wire:click="deleteCoupon"
                    spinner />
            </x-slot:actions>
        </x-list-item>
    @endif
    @if ($selected_services->isNotEmpty())
        <x-button class="btn-sm w-full">
            Hediye:
            <x-badge class="badge-neutral">
                <x-slot:value>
                    @price($this->totalGift())
                </x-slot:value>
            </x-badge>
        </x-button>
        <x-button class="btn-sm w-full">
            Ara Toplam:
            <x-badge class="badge-neutral">
                <x-slot:value>
                    @price($this->totalPrice())
                </x-slot:value>
            </x-badge>
        </x-button>
        @if ($coupon)
            <x-button class="btn-sm w-full">
                Kupon İndirimi:
                <x-badge class="badge-neutral">
                    <x-slot:value>
                        @price($couponPrice)
                    </x-slot:value>
                </x-badge>
            </x-button>
        @endif
        <x-button class="btn-sm w-full">
            Kalan:
            <x-badge class="badge-neutral">
                <x-slot:value>
                    @price($this->remainingPayment())
                </x-slot:value>
            </x-badge>
        </x-button>
        <x-button class="btn-sm w-full">
            Toplam:
            <x-badge class="badge-neutral">
                <x-slot:value>
                    @price($this->totalPriceTotal())
                </x-slot:value>
            </x-badge>
        </x-button>

    @endif
</div>
