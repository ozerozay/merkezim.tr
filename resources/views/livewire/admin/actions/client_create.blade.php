<?php

use App\Actions\Client\CreateClientAction;
use App\Models\Branch;
use App\Traits\HasCssClassAttribute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Danışan Oluştur')]
class extends Component
{
    use HasCssClassAttribute, Toast;

    public $name = '';

    public $branch_id = null;

    public $country = '90';

    public $phone;

    public $tckimlik;

    public $birth_date;

    public $il;

    public $ilce;

    public $gender = 1;

    public $adres;

    public $email;

    public function mount()
    {
        //Carbon::now();
        $this->branch_id = (new Branch)->branch_staff_first();
    }

    public function save(): void
    {
        $validator = Validator::make([
            'name' => $this->name,
            'branch_id' => $this->branch_id,
            'country' => $this->country,
            'phone' => $this->phone,
            'tckimlik' => $this->tckimlik,
            'birth_date' => $this->birth_date,
            'il' => $this->il,
            'ilce' => $this->ilce,
            'gender' => $this->gender,
            'adres' => $this->adres,
            'email' => $this->email,
        ], [
            'name' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'country' => 'required',
            'phone' => 'required|digits:10|unique:users,phone',
            'tckimlik' => 'nullable|digits:11',
            'birth_date' => 'nullable|before:now',
            'il' => 'nullable',
            'ilce' => 'nullable',
            'gender' => 'required',
            'adres' => 'nullable',
            'email' => 'nullable|email',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateClientAction::run($validator->validated());

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