<?php

namespace App\Livewire\Actions\Kasa;

use App\Actions\Spotlight\Actions\Kasa\CreateMahsupAction;
use App\Enum\PermissionType;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateMahsup extends SlideOver
{
    use Toast;

    public ?string $date;

    public ?int $giris_kasa_id = null;

    public ?int $cikis_kasa_id = null;

    public ?float $price = 0.0;

    public string $message = '';

    public function mount()
    {
        $this->date = \Carbon\Carbon::now()->format('Y-m-d');
    }

    public function save()
    {

        $validator = \Validator::make([
            'date' => $this->date,
            'price' => $this->price,
            'giris_kasa_id' => $this->giris_kasa_id,
            'cikis_kasa_id' => $this->cikis_kasa_id,
            'message' => $this->message,
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::kasa_mahsup,
        ], [
            'date' => 'required|before:tomorrow',
            'price' => 'required|min:1|decimal:0,2',
            'giris_kasa_id' => 'required|exists:kasas,id|different:cikis_kasa_id',
            'cikis_kasa_id' => 'required|exists:kasas,id',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateMahsupAction::run($validator->validated());

        $this->success(title: 'Mahsup oluşturuldu.');
        $this->close();
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function render()
    {
        return view('livewire.spotlight.actions.kasa.create-mahsup');
    }
}
