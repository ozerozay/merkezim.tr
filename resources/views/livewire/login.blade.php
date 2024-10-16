<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
    #[Layout('components.layouts.empty')]
    #[Title('Giriş')]
    class extends Component {
        use Toast;

        public  bool $section_code = false;

        #[Validate('required|digits:10')]
        public $phone = '';

        #[Validate('required')]
        public $code = '';

        public function mount()
        {
            if (Auth::user()) {
                $this->redirectIntended('/');
            }
        }

        public function submit_phone()
        {
            //$this->validate();

            $this->section_code = true;
        }

        public function submit_code()
        {
            if (Auth::user()) {
                $this->redirectIntended('/');
            }

            // Creates a new user on every login to avoid session collision
            // Just for demo purposes.

            if (User::count() > 0 ){
                $user = User::find(1);
            }else{

                $user = User::factory()->create();
            }

            Auth::login($user);
            request()->session()->regenerate();

            $this->redirectIntended('/');
        }

        public function loe($loe)
        {
            $this->warning($loe);
        }
    };
?>

<div class="mt-20 md:w-96 mx-auto">
    @if(!$section_code)
    <x-form wire:submit="submit_phone">
        <x-input label="Telefon Numaranız" wire:model="phone" icon="o-phone" autofocus inline x-mask="9999999999" />
        <x-slot:actions>
            <x-button label="Kod Gönder" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="submit_phone" />
        </x-slot:actions>
    </x-form>
    @else
    <x-form wire:submit="submit_code" class="items-center justify-center">
        <x-pin wire:model="pin" size="4" numeric autofocus @completed="$wire.submit_code" />
        <x-slot:actions>
            <x-button label="Giriş" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="submit_code" />
        </x-slot:actions>
    </x-form>
    @endif
</div>