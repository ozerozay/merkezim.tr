<?php

use App\Actions\Client\CreateNoteAction;
use App\Actions\User\CheckClientBranchAction;
use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Not Al')]
class extends Component
{
    use Toast;

    public $client;

    #[Rule('required|exists:users,id')]
    public $client_id;

    #[Rule('required')]
    public $message;

    protected $queryString = [
        'client',
    ];

    public function mount(): void
    {
        $this->client_id = User::where('unique_id', $this->client)->first()->id ?? null;
    }

    public function save(): void
    {

        CheckClientBranchAction::run($this->client_id);
        CreateNoteAction::run($this->validate());

        $this->success('Not oluşturuldu.');
        $this->reset();
    }
};
?>
<div>
    <x-header title="Not Al" separator progress-indicator="save" />
    <x-form wire:submit="save">
        <livewire:components.form.client_dropdown wire:model="client_id" />
        <x-textarea label="Notunuz" wire:model="message" placeholder="Mesajınızı yazın..." rows="3" />
        <x-slot:actions>
            <x-button label="Kaydet" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>