<?php

namespace App\Livewire\Web\Modal;

use App\Enum\WebFormStatus;
use App\Enum\WebFormType;
use App\Models\WebForm;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class RequestOfferModal extends Modal
{
    use Toast;

    public $message;
    public $email;
    public $phone;
    public $name;

    public function save(): void
    {
        try {
            $rules = [
                'message' => 'required|min:10',
                'email' => 'required|email',
                'phone' => 'required',
                'name' => 'required'
            ];

            if (auth()->check()) {
                $rules = ['message' => 'required|min:10'];
            }

            $validator = \Validator::make([
                'message' => $this->message,
                'email' => auth()->check() ? auth()->user()->email : $this->email,
                'phone' => auth()->check() ? auth()->user()->phone : $this->phone,
                'name' => auth()->check() ? auth()->user()->name : $this->name,
            ], $rules);

            if ($validator->fails()) {
                $this->error($validator->messages()->first());
                return;
            }

            if (auth()->check()) {
                $check_count = WebForm::query()
                    ->where('client_id', auth()->user()->id)
                    ->where('type', WebFormType::OFFER_REQUEST)
                    ->count();

                if ($check_count > 2) {
                    $this->error('Onay bekleyen teklif talepleriniz var.');
                    $this->close();
                    return;
                }
            }

            $request = WebForm::create([
                'client_id' => auth()->check() ? auth()->user()->id : null,
                'type' => WebFormType::OFFER_REQUEST,
                'data' => [
                    'message' => $this->message,
                    'email' => auth()->check() ? auth()->user()->email : $this->email,
                    'phone' => auth()->check() ? auth()->user()->phone : $this->phone,
                    'name' => auth()->check() ? auth()->user()->name : $this->name,
                ],
                'status' => WebFormStatus::PENDING,
            ]);

            if ($request) {
                $this->success('Talebiniz alınmıştır.');
                $this->close();
                return;
            }

            $this->error('Lütfen tekrar deneyin.');
        } catch (\Throwable $e) {
            $this->error('Lütfen tekrar deneyin.' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.client.modal.request-offer-modal');
    }
}
