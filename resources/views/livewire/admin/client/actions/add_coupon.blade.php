<?php

use App\Models\User;
use App\Peren;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    #[Modelable]
    public ?User $client_coupon = null;

    #[Rule('required|unique:coupons,code')]
    public $code;

    #[Rule('nullable')]
    public $category;

    #[Rule('required|boolean')]
    public $discount_type;

    #[Rule('required|integer')]
    public $count;

    #[Rule('required')]
    public $discount_amount;

    #[Rule('nullable|after:today')]
    public $end_date;

    #[Rule('nullable|decimal:0,2')]
    public $min_order;

    public bool $show = false;

    public function generate_code()
    {
        $this->code = Peren::unique_coupon_code();
    }

    public function boot(): void
    {
        if (! $this->client_coupon) {
            $this->show = false;
            $this->reset();

            return;
        }

        $this->fill($this->client_coupon);
        $this->show = true;
    }

    public function save(): void
    {
        $this->client_coupon->client_coupons()->create($this->validate());
        $this->reset();
        $this->dispatch('client-coupon-save');
        $this->success('Kupon oluşturuldu.');
    }
};
?>
<div>
    <x-modal wire:model="show" title="Kupon Oluştur">
        <x-hr class="mb-5" />
        <x-form wire:submit="save">
        <x-input label="Kupon Kodu" wire:model="code">
    <x-slot:append>
        <x-button label="Rastgele Oluştur" wire:click="generate_code" icon="o-check" class="btn-primary rounded-s-none" />
    </x-slot:append>
</x-input>
            <x-textarea
                label="Notunuz"
                wire:model="message"
                placeholder="Mesajınızı yazın..."
                rows="3" />

            <x-slot:actions>
                <x-button label="İptal" icon="tabler.x" @click="$dispatch('client-coupon-cancel')" />
                <x-button label="Kaydet" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>