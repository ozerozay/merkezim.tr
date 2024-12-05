<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new #[Layout('components.layouts.empty')] #[Title('Giriş')] class extends Component {
    use Toast;

    #[Livewire\Attributes\Locked]
    public $section = 'phone';

    #[Validate('required|digits:10')]
    public $phone = '';

    #[Validate('required')]
    public $code = '';

    public $gender = 1;

    public $branches;

    public $branch;

    public function mount()
    {
        if (Auth::user()) {
            $this->redirectIntended('/');
        }

        $this->branches = \App\Models\Branch::select('id', 'active', 'name')->where('active', true)->get();

        $this->branch = $this->branches->first()?->id ?? null;
    }

    public function submit_phone()
    {
        //$this->validate();

        $validator = \Validator::make(
            [
                'phone' => $this->phone,
            ],
            [
                'phone' => 'required|digits:10',
            ],
        );

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $user = User::query()
            ->select('id', 'phone', 'phone_code', 'can_login')
            ->where('phone', $this->phone)
            ->first();

        if ($user && !$user->can_login) {
            $this->error('Kullanıcı bulunamadı.');

            return;
        }

        $this->section = 'code';
    }

    public function submit_code()
    {
        /*if (Auth::user()) {
            $this->redirectIntended('/');
        }*/

        // Creates a new user on every login to avoid session collision
        // Just for demo purposes.

        $user = User::count() > 0 ? User::find(1) : User::factory()->create();

        Auth::login($user, true);
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

    <x-card title="Marge Güzellik" subtitle="Tüm işlemlerinizi 7/24 gerçkleştirin." separator progress-indicator>
        <x-slot:menu>
            <x-button icon="tabler.lock" class="btn-outline" />
        </x-slot:menu>
        @if ($section == 'phone')
            <x-form wire:submit="submit_phone">
                <x-input label="Telefon Numaranız" wire:model="phone" icon="o-phone" autofocus x-mask="9999999999"
                    hint="5xxxxxxxxx şeklinde giriş yapın." />
                <x-slot:actions>
                    <x-button label="Giriş yap veya kayıt ol" type="submit" icon="o-paper-airplane"
                        class="btn btn-primary w-full mb-4" spinner="submit_phone" />
                </x-slot:actions>
            </x-form>
        @elseif ($section == 'code')
            <x-form wire:submit="submit_code">
                <p>Doğrulama kodunu girin.</p>
                <p>+905056277636 nolu telefona gönderildi.</p>
                <x-pin wire:model="pin" wire:key="pin-{{ Str::random(10) }}" size="5" numeric autofocus
                    @completed="$wire.submit_code" />
                <x-slot:actions>
                    <x-button label="Giriş" type="submit" icon="o-paper-airplane" class="btn btn-primary w-full"
                        spinner="submit_code" />
                </x-slot:actions>
            </x-form>
            <x-hr />
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
        @elseif ($section == 'form')
            <x-form wire:submit="submit_phone">
                <p class="text-center text-xl">Son Bir Adım Kaldı</p>
                <x-select wire:key="branch-{{ Str::random(10) }}" label="Size en yakın şubemizi seçin"
                    wire:model="branch" :options="$branches" />
                <x-input label="Adınız Soyadınız" icon="tabler.user" autofocus />

                <livewire:components.form.gender_dropdown wire:key="gender-dropdown-{{ Str::random(10) }}"
                    wire:model="gender" :gender="1" :includeUniSex="false" />
                <x-checkbox label="Kampanyalardan haberdar olmak için tarafıma ticari ileti gönderilsin"
                    class="text-xxs" />
                <x-checkbox
                    label="Merkezim kullanım koşullarını, gizlilik ve KVKK politikasını ve aydınlatma metnini okudum, bu kapsamda verilerimin işlenmesini onaylıyorum" />
                <x-slot:actions>
                    <x-button label="Hadi Başlayalım" type="submit" icon="o-paper-airplane"
                        class="btn btn-primary w-full mb-4" spinner="submit_phone" />
                </x-slot:actions>
            </x-form>
        @endif
    </x-card>

    <script type="text/javascript">
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
</div>
