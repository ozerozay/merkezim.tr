<?php

use App\Actions\Client\CreateNoteAction;
use App\Actions\User\CheckClientBranchAction;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Not Al')]
class extends Component
{
    use Toast;

    #[Url]
    public $client;

    public $message;

    public function save(): void
    {

        $validator = Validator::make([
            'client_id' => $this->client,
            'message' => $this->message,
        ], [
            'client_id' => 'required|exists:users,id',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CheckClientBranchAction::run($this->client);
        CreateNoteAction::run($validator->validated());

        $this->success('Not oluşturuldu.');
        $this->reset();
    }
};
?>
<div>
    <x-header title="Not Al" separator progress-indicator="save" />
    <x-form wire:submit="save">
        <livewire:components.form.client_dropdown wire:model.live="client" />
        <x-textarea label="Notunuz" wire:model="message" placeholder="Mesajınızı yazın..." rows="3" />
        <x-slot:actions>
            <x-button label="Kaydet" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>