<?php

use App\Actions\Client\CreateClientAction;
use App\Models\Branch;
use App\Traits\HasCssClassAttribute;
use Carbon\Carbon;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Danışan Oluştur')]
class extends Component
{
    use HasCssClassAttribute, Toast;

    #[Rule('required')]
    public $name = '';

    #[Rule('required')]
    public $branch_id = null;

    #[Rule('required')]
    public $country = '90';

    #[Rule('required|digits:10|unique:users,phone')]
    public $phone;

    #[Rule('nullable|digits:11')]
    public $tckimlik;

    #[Rule('nullable|before:now')]
    public $birth_date;

    public $il;

    public $ilce;

    #[Rule('required')]
    public $gender = 1;

    #[Rule('nullable')]
    public $adres;

    #[Rule('nullable|email')]
    public $email;

    public function mount()
    {
        //Carbon::now();
        $this->branch_id = (new Branch)->branch_staff_first();
    }

    public function save(): void
    {
        CreateClientAction::run($this->validate());

        $this->resetExcept('label', 'class');
        $this->reset();
        $this->success('Danışan eklendi.');

    }
};
?>

<div>
    <x-card title="Danışan Oluştur" separator shadow>
        <x-form wire:submit="save">
            <livewire:components.form.branch_dropdown wire:model="branch_id" />
            <x-input label="Adı" wire:model="name" />
            <livewire:components.form.phone wire:model="phone" />
            <livewire:components.form.gender_dropdown wire:model="gender" :gender="1" :includeUniSex="false" />
            <x-input label="TC Kimlik" wire:model="tckimlik" x-mask="99999999999" />
            <x-input label="Adres" wire:model="adres" />
            <x-input label="E-posta" wire:model="email" />
            <livewire:components.form.date_time label="Doğum Tarihi" wire:model="birth_date" maxDate="{{ Carbon::now()->addDays(1)->format('Y-m-d') }}" />
            <x-slot:actions>
                <x-button label="Gönder" icon="o-paper-airplane" class="btn-primary" type="submit" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>