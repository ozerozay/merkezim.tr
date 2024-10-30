<?php

use App\Peren;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $payment_date = null;

    public $payment_date_config;

    public $kasa_id = null;

    public $staff_id = null;

    public $client_id = null;

    public $masraf_id = null;

    public $message = null;

    public $price = 0.0;

    public $isPayment = false;

    public bool $show = false;

    public $selectedTab = 'masraf';

    public function mount()
    {
        $this->payment_date = Carbon::now()->format('Y-m-d');
        $this->payment_date_config = Peren::dateConfig(null, Carbon::now()->format('Y-m-d'));
    }

    public function showForm($isPayment)
    {
        $this->show = true;
        $this->isPayment = $isPayment;
    }

    public function masrafSave()
    {

        $validator = Validator::make([
            'date' => $this->payment_date,
            'kasa_id' => $this->kasa_id,
            'message' => $this->message,
            'masraf_id' => $this->masraf_id,
            'price' => $this->isPayment ? $this->price * -1 : $this->price,
            'type' => 'Masraf',
        ], [
            'date' => 'required|before:tomorrow',
            'kasa_id' => 'required|exists:kasas,id',
            'message' => 'required',
            'masraf_id' => 'required|exists:masraf,id',
            'price' => 'required|decimal:0,2',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('payment-staff-payment-added', $validator->validated());
        $this->reset('masraf_id', 'kasa_id', 'price', 'message');
        //$this->show = false;
    }

    public function staffSave()
    {

        $validator = Validator::make([
            'date' => $this->payment_date,
            'kasa_id' => $this->kasa_id,
            'message' => $this->message,
            'staff_id' => $this->staff_id,
            'masraf_id' => $this->masraf_id,
            'price' => $this->isPayment ? $this->price * -1 : $this->price,
            'type' => 'Personel',
        ], [
            'date' => 'required|before:tomorrow',
            'kasa_id' => 'required|exists:kasas,id',
            'staff_id' => 'required|exists:users,id',
            'message' => 'required',
            'masraf_id' => 'required|exists:masraf,id',
            'price' => 'required|decimal:0,2',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('payment-staff-payment-added', $validator->validated());
        $this->reset('masraf_id', 'kasa_id', 'price', 'message', 'staff_id');
        //$this->show = false;
    }

    public function clientSave()
    {

        $validator = Validator::make([
            'date' => $this->payment_date,
            'kasa_id' => $this->kasa_id,
            'message' => $this->message,
            'client_id' => $this->client_id,
            'masraf_id' => $this->masraf_id,
            'price' => $this->isPayment ? $this->price * -1 : $this->price,
            'type' => 'Danışan',
        ], [
            'date' => 'required|before:tomorrow',
            'kasa_id' => 'required|exists:kasas,id',
            'client_id' => 'required|exists:users,id',
            'message' => 'required',
            'masraf_id' => 'required|exists:masraf,id',
            'price' => 'required|decimal:0,2',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->dispatch('payment-staff-payment-added', $validator->validated());
        $this->reset('masraf_id', 'kasa_id', 'price', 'message', 'client_id');
        //$this->show = false;
    }
};

?>
<div>
    <x-button label="Ödeme Yap" wire:click="showForm(true)" icon="o-minus" class="btn-outline"/>
    <x-button label="Ödeme Al" wire:click="showForm(false)" wire:confirm="Ödeme almak istediğinizden emin misiniz ?"
              icon="o-plus" class="btn-outline"/>
    <template x-teleport="body">

        <x-modal wire:model="show" title="Ödeme">
            <x-tabs wire:model="selectedTab">
                <x-tab name="masraf" label="Masraf">
                    <div>
                        <x-form wire:submit="masrafSave">
                            <x-datepicker label="Tarih" wire:model="payment_date" icon="o-calendar"
                                          :config="$payment_date_config"/>
                            <livewire:components.form.kasa_dropdown wire:model="kasa_id"/>
                            <livewire:components.form.masraf_dropdown wire:model="masraf_id"/>
                            <x-input label="Tutar" wire:model="price" suffix="₺" money/>
                            <x-input label="Açıklama" wire:model="message"/>
                            <x-slot:actions>
                                <x-button label="Gönder" icon="o-paper-airplane" type="submit" spinner="masrafSave"
                                          class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </div>
                </x-tab>
                <x-tab name="staff" label="Personel">
                    <div>
                        <x-form wire:submit="staffSave">
                            <x-datepicker label="Tarih" wire:model="payment_date" icon="o-calendar"
                                          :config="$payment_date_config"/>
                            <livewire:components.form.masraf_dropdown wire:model="masraf_id"/>
                            <livewire:components.form.staff_dropdown wire:model="staff_id"/>
                            <livewire:components.form.kasa_dropdown wire:model="kasa_id"/>
                            <x-input label="Tutar" wire:model="price" suffix="₺" money/>
                            <x-input label="Açıklama" wire:model="message"/>
                            <x-slot:actions>
                                <x-button label="Gönder" icon="o-paper-airplane" type="submit" spinner="staffSave"
                                          class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </div>
                </x-tab>
                <x-tab name="client" label="Danışan">
                    <div>
                        @if(!$isPayment)
                            <x-alert title="Danışandan ödeme almak için 'Tahsilat' sayfasını kullanın."
                                     icon="o-exclamation-triangle"/>
                        @endif
                        <x-form wire:submit="clientSave">
                            <x-datepicker label="Tarih" wire:model="payment_date" icon="o-calendar"
                                          :config="$payment_date_config"/>
                            <livewire:components.form.masraf_dropdown wire:model="masraf_id"/>
                            <livewire:components.form.client_dropdown wire:model="client_id"/>
                            <livewire:components.form.kasa_dropdown wire:model="kasa_id"/>
                            <x-input label="Tutar" wire:model="price" suffix="₺" money/>
                            <x-input label="Açıklama" wire:model="message"/>
                            <x-slot:actions>
                                <x-button label="Gönder" icon="o-paper-airplane" type="submit" spinner="clientSave"
                                          class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </div>
                </x-tab>
            </x-tabs>

        </x-modal>
    </template>
</div>
