<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast, \App\Traits\HasCssClassAttribute;

    public bool $show = false;

    public string $label = 'Oluştur';

    public ?string $name = null;

    public ?string $phone = null;

    public ?string $message = null;

    public ?string $type = \App\TalepType::diger->name;

    public ?int $branch_id = null;

    public function showForm(): void
    {
        $this->reset();
        $this->show = true;
    }

    public function save(): void
    {
        $validator = Validator::make([
            'branch_id' => $this->branch_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'type' => $this->type,
            'message' => $this->message,
            'date' => date('Y-m-d'),
            'user_id' => auth()->user()->id
        ], [
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required',
            'phone' => 'required|unique:taleps,phone|unique:users,phone',
            'type' => 'required',
            'message' => 'required',
            'date' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return;
        }

        \App\Models\Talep::create($validator->validated());

        $this->show = false;
        $this->dispatch('refresh-taleps');
        $this->success('Talep oluşturuldu.');
    }
};

?>
<div>
    <x-button :label="$label" wire:click="showForm" icon="o-plus" class="btn-primary {{ $class }}" responsive/>
    <template x-teleport="body">
        <x-modal wire:model="show" title="Talep Oluştur">
            <hr class="mb-5"/>
            <x-form wire:submit="save">
                <livewire:components.form.branch_dropdown wire:model="branch_id"/>
                <x-input label="Adı" wire:model="name"/>
                <livewire:components.form.phone wire:model="phone"/>
                <livewire:components.form.talep_type_dropdown wire:model="type"/>
                <x-input label="Açıklama" wire:model="message"/>
                <x-slot:actions>
                    <x-button label=" İptal" @click="$wire.show = false"/>
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit"/>
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>
