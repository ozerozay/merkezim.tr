<?php

namespace App\Livewire\Web\Modal;

use App\Enum\ClientRequestType;
use App\Models\ClientRequest;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class RequestOfferModal extends Modal
{
    use Toast;

    public $message;

    public function save(): void
    {
        try {

            $validator = \Validator::make([
                'message' => $this->message,
                'client_id' => auth()->user()->id,
            ], [
                'message' => 'required',
                'client_id' => 'required',
            ]);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());

                return;
            }

            $check_count = ClientRequest::query()
                ->where('client_id', auth()->user()->id)
                ->where('type', ClientRequestType::offer_request->name)
                ->count();

            if ($check_count > 2) {
                $this->error('Onay bekleyen teklif talepleriniz var.');
                $this->close();

                return;
            }

            $request = ClientRequest::create([
                'client_id' => auth()->user()->id,
                'message' => $this->message,
                'type' => ClientRequestType::offer_request->name,
            ]);

            if ($request) {
                $this->success('Talebiniz alınmıştır.');
                $this->close();

                return;
            }

            $this->error('Lütfen tekrar deneyin.');

        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.'.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.client.modal.request-offer-modal');
    }
}
