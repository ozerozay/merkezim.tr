<?php

use App\Actions\Kasa\CreateKasaMahsupAction;
use App\Actions\User\CheckUserInstantApprove;
use App\Actions\User\CreateApproveRequestAction;
use App\ApproveTypes;
use App\Peren;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('Mahsup')]
class extends Component
{
    use Toast;

    public ?string $date;

    public ?int $giris_kasa_id = null;

    public ?int $cikis_kasa_id = null;

    public ?float $price = 0.0;

    public string $message = '';

    public $date_config;

    public function mount()
    {
        $this->date_config = Peren::dateConfig(null, Carbon::now()->format('Y-m-d'));
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function save()
    {

        $validator = Validator::make([
            'date' => $this->date,
            'price' => $this->price,
            'giris_kasa_id' => $this->giris_kasa_id,
            'cikis_kasa_id' => $this->cikis_kasa_id,
            'message' => $this->message,
            'user_id' => auth()->user()->id,
        ], [
            'date' => 'required|before:tomorrow',
            'price' => 'required|min:1|decimal:0,2',
            'giris_kasa_id' => 'required|exists:kasas,id|different:cikis_kasa_id',
            'cikis_kasa_id' => 'required|exists:kasas,id',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        if (CheckUserInstantApprove::run(auth()->user()->id)) {
            CreateKasaMahsupAction::run($validator->validated());

            $this->success('Mahsup gerçekleştirildi.');
        } else {
            CreateApproveRequestAction::run($validator->validated(), auth()->user()->id, ApproveTypes::mahsup, $this->message);

            $this->success(Peren::$approve_request_ok);
        }

    }
};

?>
<div>
    <x-card title="Mahsup" progress-indicator separator>
        <x-form wire:submit="save">
            <x-datepicker label="Tarih" wire:model="date" icon="o-calendar" :config="$date_config" />
            <livewire:components.form.kasa_dropdown wire:model="cikis_kasa_id" label="Çıkış Kasası" />
            <livewire:components.form.kasa_dropdown wire:model="giris_kasa_id" label="Giriş Kasası" />
            <x-input label="Tutar" wire:model="price" suffix="₺" money />
            <x-input label="Açıklama" wire:model="message" />
            <x:slot:actions>
                <x-button label="Gönder" wire:click="save" spinner="save" icon="o-paper-airplane" class="btn-primary" />
            </x:slot:actions>
        </x-form>
    </x-card>
</div>