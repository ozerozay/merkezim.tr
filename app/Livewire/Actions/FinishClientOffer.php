<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\FinishClientOfferAction;
use App\Enum\PermissionType;
use App\Models\Offer;
use App\Models\SaleType;
use App\Rules\PriceValidation;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class FinishClientOffer extends SlideOver
{
    use Toast;

    public int|Offer $offer;

    public $kasa_id;

    public $price;

    public $message;

    public function mount(Offer $offer)
    {
        $this->offer = $offer->load('client');
        $this->price = $offer->price;
    }

    public function save(): void
    {
        try {

            $service_items = [];
            foreach ($this->offer->items as $item) {
                $service_items[] = [
                    $item->itemable_type == "service" ? "service_id" : "package_id" => $item->itemable_id,
                    'type' => $item->itemable_type == "service" ? "service" : "package",
                    'quantity' => $item->quantity,
                    'gift' => $item->gift,
                ];
            }

            $pesinats[] = [
                'kasa_id' => $this->kasa_id,
                'date' => date('Y-m-d'),
                'price' => $this->price,
                'message' => $this->offer->unique_id . ' nolu teklif alınan peşinat',
            ];

            $validator = \Validator::make([
                'offer_id' => $this->offer->id,
                'client_id' => $this->offer->client_id,
                'sale_type_id' => SaleType::firstOrCreate(['name' => 'TEKLİF', 'active' => true])->id,
                'date' => date('Y-m-d'),
                'staff_ids' => [$this->offer->user_id],
                'sale_price' => $this->price,
                'services' => $service_items,
                'nakits' => $pesinats,
                'taksits' => null,
                'price_real' => $this->offer->price,
                'message' => $this->message,
                'user_id' => auth()->user()->id,
                'expire_date' => $this->offer->month ? $this->offer->expire_date->addMonths($this->offer->month) : null,
                'coupon_id' => null,
                'permission' => PermissionType::action_client_finish_offer->name,
            ], [
                'offer_id' => 'required|exists:offers,id',
                'client_id' => 'required|exists:users,id',
                'sale_type_id' => 'required|exists:sale_types,id',
                'date' => 'required',
                'staff_ids' => 'nullable|array',
                'sale_price' => ['required', new PriceValidation],
                'services' => 'required',
                'nakits' => 'nullable',
                'taksits' => 'nullable',
                'price_real' => 'decimal:0,2',
                'message' => 'required',
                'user_id' => 'required',
                'expire_date' => 'nullable',
                'permission' => 'required',
                'coupon_id' => 'nullable',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }


            $sale_no = FinishClientOfferAction::run($validator->validated(), $approve = false);

            $this->success('Teklif tamamlandı.');
            $this->close();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.spotlight.actions.finish-client-offer');
    }
}
