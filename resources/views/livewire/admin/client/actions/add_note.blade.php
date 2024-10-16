<?php

use App\Models\User;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    #[Modelable]
    public ?User $client_note = null;

    #[Rule('required')]
    public $message;

    public bool $show = false;

    public function boot(): void
    {
        if (! $this->client_note) {
            $this->show = false;
            $this->message = null;
            return;
        }

        $this->fill($this->client_note);
        $this->show = true;
    }

    public function save(): void
    {
        $this->client_note->client_notes()->create($this->validate());
        $this->reset();
        $this->dispatch('client-note-saved');
        $this->success('Not eklendi.');
    }
};
?>
<div>
    <x-modal wire:model="show" title="Not Al">
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
            <x-textarea
                label="Notunuz"
                wire:model="message"
                placeholder="Mesajınızı yazın..."
                rows="3" />

            <x-slot:actions>
                <x-button label="İptal" icon="tabler.x" @click="$dispatch('client-note-cancel')" />
                <x-button label="Kaydet" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>