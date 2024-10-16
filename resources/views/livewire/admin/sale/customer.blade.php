<?php

use App\Models\User;
use App\Traits\StringHelper;
use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?int $client_id = null;

    public function confirm()
    {
        if (is_null($this->client_id)) {
            $this->error('Lütfen danışan seçin.');

            return;
        }
        if (! User::checkClientBranch($this->client_id)) {
            $this->error('Bu danışan için yetkiniz bulunmuyor.');

            return;
        }

        $client_unique_id = User::find($this->client_id)->unique_id;

        return redirect()->route('admin.actions.client_sale_create', ['client' => $client_unique_id]);
    }
};
?>
<div>
    <x-header title="Satış Oluştur" separator progress-indicator />
    <div class="grid lg:grid-cols-2 gap-8">
        <div class="content-start">
            <x-card title="Danışan" separator shadow>
                <livewire:components.form.client_dropdown wire:model.live="client_id" />
                <x-slot:actions>
                    @if($client_id)
                        <x-button label="Onayla" wire:click="confirm" icon="o-check" class="btn-primary" />
                    @endif
                </x-slot:actions>
            </x-card>
        </div>

        <div>
            <img src="{{ asset('images/new-order.png') }}" class="mx-auto" width="300px" />
        </div>
    </div>
</div>