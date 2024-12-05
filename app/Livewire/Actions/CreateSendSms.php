<?php

namespace App\Livewire\Actions;

use App\Models\SMSTemplate;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateSendSms extends SlideOver
{
    use Toast;

    public int|User $client;

    public $message;

    public $phone;

    public $template;

    public $client_id;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->client_id = $client->id;
        $this->phone = $client->phone;
    }

    public function updatedTemplate($value)
    {
        $template = SMSTemplate::find($value);
        if ($template) {
            $this->message = $template->message;
        }
    }

    public function updatedClientId($value)
    {
        $user = User::find($value);
        $this->phone = $user ? $user->phone : null;
    }

    public function save()
    {
        $dumduzMetin = preg_replace('/\s+/', ' ', $this->message);

        $inputLength = strlen($dumduzMetin); // 309
        $boyut = ceil($inputLength / 155); // 309 / 155 = 1.993 → ceil ile 2
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-send-sms');
    }
}
