<?php

use App\Models\User;
use App\Traits\HasCssClassAttribute;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, HasCssClassAttribute;

    #[Rule('required')]
    public $name = '';

    #[Rule('required')]
    public $branch_id = null;

    #[Rule('required')]
    public $country = '90';

    #[Rule('required|unique:users,phone')]
    public $phone;

    #[Rule('nullable')]
    public $tckimlik;

    #[Rule('nullable')]
    public $birth_date;

    #[Rule('nullable')]
    public $il;

    #[Rule('nullable')]
    public $ilce;

    #[Rule('nullable')]
    public $gender;

    #[Rule('nullable')]
    public $adres;

    #[Rule('nullable|email')]
    public $email;


    public bool $show = false;

    public string $label = 'Oluştur';

    public function save(): void
    {
        debugbar()->info($this->validate());
        User::create($this->validate());

        $this->show = false;
        $this->resetExcept('label', 'class');
        $this->dispatch('client-saved');
        $this->success('Danışan eklendi.');
    }
};
?>
<div>
    <x-button :label="$label" @click="$wire.show = true" icon="o-plus" class="btn-primary {{ $class }}" responsive />
    <template x-teleport="body">
        <x-modal wire:model="show" title="Danışan Oluştur">
            <hr class="mb-5" />
            <x-form wire:submit="save">
                <x-errors title="Hata!" icon="o-face-frown" />
                <x-input label="Adı" wire:model="name" />
                <livewire:components.form.branch_dropdown wire:model="branch_id" />
                <livewire:components.form.phone wire:model="phone" />
                <livewire:components.form.gender_dropdown wire:model="gender" />
                <x-input label="TC Kimlik" wire:model="tckimlik" x-mask="99999999999" />
                <x-input label="Adres" wire:model="adres" />
                <x-input label="E-posta" wire:model="email" />
                <livewire:components.form.date_time label="Doğum Tarihi" wire:model="birth_date" />
                <x-slot:actions>
                    <x-button label="İptal" @click="$wire.show = false" />
                    <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
                </x-slot:actions>
            </x-form>
        </x-modal>
    </template>
</div>