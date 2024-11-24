<?php

namespace App\Traits;

use App\Models\Kasa;
use App\Models\Package;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

trait ServicePackageProductHandler
{
    use Toast;

    public $price = 0.0;

    public $service_collection;

    public bool $checkPaymentTotalPrice = true;

    public ?Collection $selected_services;

    public ?Collection $selected_payments;

    #[On('modal-payment-added')]
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
                $this->warning('Tutar '.$payment['price'].' olarak güncellendi.');
            }
        }

        $kasa = Kasa::where('id', $payment['kasa_id'])->first();
        $lastId = $this->selected_payments->last() != null ? $this->selected_payments->last()['id'] + 1 : 1;
        $this->selected_payments->push([
            'id' => $lastId,
            'kasa_id' => $kasa->id,
            'kasa_name' => $kasa->name,
            'price' => $payment['price'],
            'date' => $payment['date'],
        ]);
    }

    #[On('modal-service-added')]
    public function addService($service): void
    {
        $services = Service::whereIn('id', $service['service_ids'])->get();

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
    }

    #[On('modal-product-added')]
    public function addProduct($product): void
    {
        $products = Product::whereIn('id', $product['product_ids'])->get();

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
    }

    #[On('modal-package-added')]
    public function addPackage($info): void
    {
        $package = Package::where('id', $info['package_ids'])->first();

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
    }

    public function deleteItem($id, $type): void
    {
        $this->selected_services = $this->selected_services->reject(function ($item) use ($id, $type) {
            return $item['id'] == $id && $item['type'] == $type;
        });
    }

    public function deletePayment($id): void
    {
        $this->selected_payments = $this->selected_payments->reject(function ($item) use ($id) {
            return $item['id'] == $id;
        });
    }

    #[Computed]
    public function totalPrice()
    {
        if ($this->price > 0) {
            return $this->price;
        }

        return $this->selected_services->where('gift', false)->sum('price');
    }

    #[Computed]
    public function totalPayment()
    {
        return $this->selected_payments->sum('price');
    }

    #[Computed]
    public function remainingPayment()
    {
        return $this->totalPrice() - $this->totalPayment();
    }

    #[Computed]
    public function totalGift()
    {
        return $this->selected_services->where('gift', true)->sum('price');
    }
}
