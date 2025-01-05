<div class="relative overflow-x-hidden overflow-y-auto">
    <!-- Loading Overlay -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <div class="loading loading-spinner loading-lg text-primary"></div>
                <p class="text-base-content/70 font-medium">Lütfen bekleyin...</p>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="p-4 border-b border-base-200 bg-base-100/95 sticky top-0 z-40 backdrop-blur-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary/10 rounded-xl">
                    <i class="text-xl text-primary">💳</i>
                </div>
                <div>
                    <h2 class="text-lg font-bold">{{ \App\Enum\PaymentType::from($type)->label() }}</h2>
                    <p class="text-sm text-base-content/70">Güvenli ödeme işlemi</p>
                </div>
            </div>

            @if ($frame_code == null)
                <x-button icon="tabler.x" class="btn-ghost" wire:click="$dispatch('slide-over.close', { force: true })"/>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    @if ($frame_code)
        <div class="h-screen">
            <div class="h-full w-full">
                <iframe id="paymentFrame" name="paymentFrame" srcdoc="{{$frame_code}}"
                        style="width:100%; height:100%; border:none;"></iframe>
            </div>
        </div>
    @else
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @if ($frame_error)
                <x-alert title="Hata" description="{{ $frame_error }}" class="alert-error" dismissible/>
            @endif

            <!-- Ödeme Yöntemi Seçimi -->
            @if ($this->paymentTypes->count() > 1)
                <div class="bg-base-100 border border-base-200 p-4 rounded-lg">
                    <div class="flex justify-center gap-0">
                        @if ($this->paymentTypes->contains('kk'))
                            <x-button icon="{{ $selectedMethod == 1 ? 'tabler.check' : 'tabler.credit-card' }}"
                                wire:click="changeMethod(1)"
                                class="w-1/2 px-4 py-2 font-medium rounded-l-full {{ $selectedMethod == 1 ? 'btn-primary' : 'btn-outline' }}">
                                Kredi Kartı
                            </x-button>
                        @endif
                        @if ($this->paymentTypes->contains('havale'))
                            <x-button wire:click="changeMethod(2)"
                                icon="{{ $selectedMethod == 2 ? 'tabler.check' : 'tabler.cash-banknote' }}"
                                class="w-1/2 px-4 py-2 font-medium rounded-r-full {{ $selectedMethod == 2 ? 'btn-primary' : 'btn-outline' }}">
                                Havale
                            </x-button>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Kredi Kartı Formu -->
            @if ($selectedMethod == 1)
                <div class="bg-base-100 border border-base-200 p-4 rounded-lg">
                    <form wire:submit.prevent="processPayment" class="space-y-4">
                        <div x-data="{
                            cardNumber: '',
                            previousValue: '',
                            handleCheck() {
                                const currentValue = this.cardNumber;
                                if (currentValue.length > this.previousValue.length) {
                                    if (currentValue.replace(/\D/g, '').length === 8) {
                                        $wire.dispatchSelf('cardNumberValidated');
                                    }
                                }
                                this.previousValue = currentValue;
                            }
                        }">
                            <x-input inputmode="numeric" 
                                label="Kredi Kartı Numaranız" 
                                wire:model="cardNumber"
                                icon="o-credit-card" 
                                placeholder="9999-9999-9999-9999" 
                                maxLength="19"
                                hint="Taksit seçenekleri için kart numaranızı girin"
                                @input="handleCheck()"
                                x-model="cardNumber"
                                clearable
                                x-mask="9999-9999-9999-9999"/>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <x-input inputmode="numeric" 
                                placeholder="AA/YY" 
                                label="SKT"
                                wire:model="expiryDate" 
                                maxLength="5" 
                                icon="o-calendar-days"
                                clearable
                                x-mask="99/99"/>

                            <x-password clearable 
                                inputmode="numeric" 
                                maxLength="3" 
                                placeholder="CVV"
                                label="CVV"
                                wire:model="cvv" 
                                icon="o-credit-card" 
                                x-mask="999" 
                                only-password/>
                        </div>

                        <x-input clearable 
                            label="Kartın Üzerinde Yazan İsim" 
                            wire:model="cardName"
                            icon="o-user"/>

                        @if ($taksit_orans->isNotEmpty())
                            <div class="bg-base-100 p-4 border border-base-200 rounded-lg">
                                <div class="space-y-2">
                                    <label class="label cursor-pointer">
                                        <span class="label-text font-medium">PEŞİN</span>
                                        <input wire:model.live="selectedInstallment" 
                                            type="radio" 
                                            name="radio-10"
                                            class="radio checked:bg-green-500"
                                            value="pesin"/>
                                    </label>

                                    @foreach($taksit_orans as $key=>$oran)
                                        @php
                                            $oran_price = $data * (float) $oran / 100;
                                        @endphp
                                        <label class="label cursor-pointer">
                                            <span class="label-text">
                                                {{ $loop->index + 2 }} TAKSİT 
                                                <i>+@price($oran_price)</i>
                                            </span>
                                            <input wire:model.live="selectedInstallment" 
                                                type="radio" 
                                                name="radio-10"
                                                class="radio checked:bg-blue-500" 
                                                value="{{$loop->index + 2}}"/>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <input type="checkbox" class="checkbox checked:bg-blue-500" checked/>
                                <span class="label-text text-sm">
                                    Ön bilgilendirme ve mesafeli satış sözleşmesini kabul ediyorum.
                                </span>
                            </label>
                        </div>

                        <img src="{{ asset('/bankalar-tek-parca.jpg') }}" alt="Bankalar" class="w-full"/>
                    </form>
                </div>
            @endif

            <!-- Havale Formu -->
            @if ($selectedMethod == 2)
                <x-alert title="Hatırlatma"
                    description="Ödemenizi gerçekleştirdikten sonra, ödeme yaptım butonuna dokunun."
                    icon="tabler.check" 
                    class="alert-info"/>

                <x-accordion wire:model="havale_group" separator class="bg-base-200">
                    @foreach($havale_accounts as $account)
                        <x-collapse name="group{{ $loop->index }}">
                            <x-slot:heading>{{ $account->name }}</x-slot:heading>
                            <x-slot:content>
                                <x-input readonly
                                    class="mb-3"
                                    value="{{ $account->account_name }}">
                                    <x-slot:append>
                                        <x-button icon="tabler.copy" 
                                            class="btn-primary rounded-s-none"
                                            x-data
                                            @click="navigator.clipboard.writeText('{{ $account->account_name }}')"/>
                                    </x-slot:append>
                                </x-input>
                                <x-input readonly 
                                    value="{{ $account->iban }}">
                                    <x-slot:append>
                                        <x-button icon="tabler.copy" 
                                            class="btn-primary rounded-s-none"
                                            x-data
                                            @click="navigator.clipboard.writeText('{{ $account->iban }}')"/>
                                    </x-slot:append>
                                </x-input>
                            </x-slot:content>
                        </x-collapse>
                    @endforeach
                </x-accordion>
            @endif

            <!-- Fatura Bilgisi -->
            <label wire:click="$dispatch('modal.open', {component: 'web.modal.fatura-modal'})"
                class="bg-base-100 p-4 rounded-lg cursor-pointer border border-base-200 flex items-center gap-4">
                <x-icon name="tabler.paperclip"/>
                <div class="flex-1">
                    <span class="text-lg font-medium">
                        {{ $fatura != null ? 'Vergi/TCKN: ' . $fatura : 'Fatura bilgisi seçin' }}
                    </span>
                </div>
            </label>

            <!-- Kupon Kodu -->
            @if ($type == \App\Enum\PaymentType::cart)
                <label class="bg-base-100 p-4 rounded-lg cursor-pointer border border-base-200 flex items-center gap-4">
                    <x-icon name="o-gift"/>
                    <div class="flex-1">
                        <span class="text-lg font-medium">Kupon Kullan</span>
                    </div>
                </label>
            @endif
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-base-200 bg-base-100">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Ara Toplam</span>
                    <span class="font-medium">@price($this->calculateAraToplam())</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Vergi</span>
                    <span class="font-medium">@price($this->calculateVergi())</span>
                </div>
                @if ($this->calculateDiscount() > 0)
                    <div class="flex justify-between text-sm">
                        <span>İndirim</span>
                        <span class="font-medium text-success">-@price($this->calculateDiscount())</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span>Komisyon</span>
                    <span class="font-medium">@price($this->calculateKomisyon())</span>
                </div>
                <div class="flex justify-between text-base font-bold pt-2 border-t border-base-200">
                    <span>Toplam</span>
                    <span>@price($this->calculateTotalAndKomisyon())</span>
                </div>

                @if ($selectedMethod == 1)
                    <x-button icon="tabler.lock" 
                        label="ÖDEMEYİ TAMAMLA"
                        wire:click="creditCardCheckout"
                        class="btn-primary w-full"/>
                @else
                    <x-button icon="tabler.lock" 
                        label="{{ $this->calculateTotalAndKomisyon() }} TL - ÖDEME YAPTIM"
                        wire:click="submitHavale"
                        class="btn-primary w-full"/>
                @endif
            </div>
        </div>
    @endif
</div>
