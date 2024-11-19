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

    public bool $section_code = false;

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
        /*if (Auth::user()) {
            $this->redirectIntended('/');
        }*/

        // Creates a new user on every login to avoid session collision
        // Just for demo purposes.

        if (User::count() > 0) {
            $user = User::find(1);
        } else {

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

    <x-card title="Marge Güzellik" subtitle="Tüm işlemlerinizi 7/24 gerçkleştirin." separator
            progress-indicator>
        <x-slot:menu>
            <x-button icon="tabler.lock" class="btn-outline"/>
        </x-slot:menu>
        @if(!$section_code)
            <x-form wire:submit="submit_phone">
                <x-input label="Telefon Numaranız" wire:model="phone" icon="o-phone" autofocus x-mask="9999999999"
                         hint="5xxxxxxxxx şeklinde giriş yapın."/>
                <x-slot:actions>
                    <x-button label="Giriş yap veya kayıt ol" type="submit" icon="o-paper-airplane"
                              class="btn btn-primary w-full mb-4"
                              spinner="submit_phone"/>
                </x-slot:actions>
            </x-form>
        @else
            <x-form wire:submit="submit_code">
                <p>Doğrulama kodunu girin.</p>
                <p>+905056277636 nolu telefona gönderildi.</p>
                <x-pin wire:model="pin" size="5" numeric autofocus @completed="$wire.submit_code"/>
                <x-slot:actions>
                    <x-button label="Giriş" type="submit" icon="o-paper-airplane" class="btn btn-primary w-full"
                              spinner="submit_code"/>
                </x-slot:actions>
            </x-form>
            <x-hr/>
            <div class="grid gap-1 grid-cols-3">
                <x-button class="w-full btn-outline col-span-1">
                    Geri Dön
                </x-button>
                <div class="grid gap-y-2 text-center col-span-2" x-data="otpSend(2)" x-init="init()">
                    <template x-if="getTime() <= 0">
                        <form wire:submit="resendOtp">
                            <x-button class="w-full btn-outline">
                                Tekrar gönder
                            </x-button>
                            <input type="hidden" wire:model="otp">
                        </form>
                    </template>
                    <template x-if="getTime() > 0">
                        <small>
                            <x-button class="w-full btn-outline" disabled>
                                Tekrar göndermek için: <span x-text="formatTime(getTime())"></span>
                            </x-button>
                        </small>
                    </template>
                </div>
            </div>

            <script>
                function otpSend(num) {
                    const milliseconds = num * 1000 //60 seconds
                    const currentDate = Date.now() + milliseconds
                    var countDownTime = new Date(currentDate).getTime()
                    let interval;
                    return {
                        countDown: milliseconds,
                        countDownTimer: new Date(currentDate).getTime(),
                        intervalID: null,
                        init() {
                            if (!this.intervalID) {
                                this.intervalID = setInterval(() => {
                                    this.countDown = this.countDownTimer - new Date().getTime();
                                }, 1000);
                            }
                        },
                        getTime() {
                            if (this.countDown < 0) {
                                this.clearTimer()
                            }
                            return this.countDown;
                        },
                        formatTime(num) {
                            var date = new Date(num);
                            return new Date(this.countDown).toLocaleTimeString(navigator.language, {
                                minute: '2-digit',
                                second: '2-digit'
                            });
                        },
                        clearTimer() {
                            clearInterval(this.intervalID);
                        }
                    }
                }
            </script>
        @endif
    </x-card>


</div>
