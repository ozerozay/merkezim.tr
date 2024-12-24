<div>
    <div class="fixed inset-y-0 right-0 w-full max-w-md shadow-lg flex flex-col h-screen  bg-base-100">
        <!-- Başlık -->
        <div class="p-4 border-b border-base-200 flex justify-between items-center">
            <h1 class="text-lg font-semibold">{{ \App\Enum\PaymentType::from($type)->label() }}</h1>
            <x-button icon="tabler.x" class="btn-sm btn-outline text-gray-600 ml-auto"
                      wire:click="$dispatch('slide-over.close')"/>
        </div>

        <div wire:loading>
            <!-- Overlay for this div -->
            <div class="absolute inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-10">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <!-- Loading Spinner -->
                    <div class="flex items-center justify-center">
                        <svg class="animate-spin h-16 w-16 text-black-500" xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </div>
                    <!-- Loading Text -->
                    <p class="text-black-500 text-xl font-semibold">Lütfen bekleyin...</p>
                </div>
            </div>
        </div>
        @if ($frame_code)
            <iframe id="paymentFrame" name="paymentFrame" srcdoc="{{$frame_code}}"
                    style="width:100%; height:100%; border:none;"></iframe>
        @else

            <div class="flex-1 overflow-y-auto p-4 space-y-4 ">

                @if ($frame_error)
                    <x-alert title="Hata" description="{{ $frame_error }}" class="alert-error" dismissible/>
                @endif

                <div class="bg-base-100 border border-gray-600 p-4 rounded-lg">
                    <div class="flex justify-center gap-0">
                        @if ($this->paymentTypes->contains('kk'))
                            <x-button
                                icon="{{ $selectedMethod == 1 ? 'tabler.check' : 'tabler.credit-card' }}"
                                wire:click="changeMethod(1)"
                                class="w-1/2 px-4 py-2  font-medium rounded-l-full {{ $selectedMethod == 1 ? 'btn-primary' : 'btn-outline' }}">
                                Kredi Kartı
                            </x-button>
                        @endif
                        @if ($this->paymentTypes->contains('havale'))
                            <x-button
                                wire:click="changeMethod(2)"
                                icon="{{ $selectedMethod == 2 ? 'tabler.check' : 'tabler.cash-banknote' }}"
                                class="w-1/2 px-4 py-2  font-medium rounded-r-full {{ $selectedMethod == 2 ? 'btn-primary' : 'btn-outline' }}">
                                Havale
                            </x-button>
                        @endif
                    </div>
                </div>
                @if ($selectedMethod == 1)
                    <div class="bg-base-100 border border-gray-600 p-4 rounded-lg flex items-center gap-4">
                        <form wire:submit.prevent="processPayment">

                            <div class="form-control mb-4">
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
                                    <x-input inputmode="numeric" label="Kredi Kartı Numaranız" wire:model="cardNumber"
                                             icon="o-credit-card" placeholder="9999-9999-9999-9999" maxLength="19"
                                             hint="Taksit seçenekleri için kart numaranızı girin"
                                             @input="handleCheck()"
                                             x-model="cardNumber"
                                             clearable
                                             placeholder="Kart numaranızı girin"
                                             x-mask="9999-9999-9999-9999">
                                    </x-input>

                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="form-control mb-4">
                                    <x-input inputmode="numeric" placeholder="GG/AA" label="Son Kullanma Tarihi"
                                             wire:model="expiryDate" maxLength="5" icon="o-calendar-days"
                                             clearable
                                             x-mask="99/99"/>
                                </div>
                                <div class="form-control mb-4">
                                    <x-password clearable inputmode="numeric" maxLength="3" placeholder="CVV"
                                                label="CVV"
                                                wire:model="cvv" icon="o-credit-card" x-mask="999" only-password/>
                                </div>
                            </div>
                            <div class="form-control mb-4">
                                <x-input clearable label="Kartın Üzerinde Yazan İsim" wire:model="cardName"
                                         icon="o-user"/>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer">
                                    <input type="checkbox" name="radio-10" class="checkbox checked:bg-blue-500"
                                           checked="checked"/>
                                    <span
                                        class="label-text text-sm">Ön bilgilendirme ve mesafeli satış sözleşmesini kabul ediyorum.</span>
                                </label>
                            </div>


                            <img src="{{ asset('/bankalar-tek-parca.jpg') }}"/>
                        </form>
                    </div>

                    @if ($taksit_orans->isNotEmpty())
                        <div class="bg-base-100 p-4 border border-gray-600 rounded-lg">
                            <div class="form-control">

                                <label class="label cursor-pointer">
                                    <span class="label-text text-bold">PEŞİN</span>
                                    <input wire:model.live="selectedInstallment" type="radio" name="radio-10"
                                           class="radio checked:bg-green-500"
                                           value="pesin"
                                    />
                                </label>
                                @foreach($taksit_orans as $key=>$oran)
                                    @php
                                        $oran_price = $this->calculateTotal() * (float) $oran / 100;
                                    @endphp

                                    <label class="label cursor-pointer">
                                    <span
                                        class="label-text">{{ $loop->index + 2 }} TAKSİT <i>+@price($oran_price)</i></span>
                                        <input wire:model.live="selectedInstallment" type="radio" name="radio-10"
                                               class="radio checked:bg-blue-500" value="{{$loop->index + 2}}"/>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
                @if ($selectedMethod == 2)
                    <x-accordion wire:model="havale_group" separator class="bg-base-200">
                        @foreach($havale_accounts as $account)
                            <x-collapse name="group{{ $loop->index }}">
                                <x-slot:heading>{{ $account->name }}</x-slot:heading>
                                <x-slot:content>
                                    <x-input readonly
                                             class="mb-3"
                                             value="{{ $account->account_name }}">
                                        <x-slot:append>
                                            <x-button icon="tabler.copy" class="btn-primary rounded-s-none"/>
                                        </x-slot:append>
                                    </x-input>
                                    <x-input readonly value="{{ $account->iban }}">
                                        <x-slot:append>
                                            <x-button icon="tabler.copy" class="btn-primary rounded-s-none"/>
                                        </x-slot:append>
                                    </x-input>
                                </x-slot:content>
                            </x-collapse>
                        @endforeach
                    </x-accordion>
                @endif
                <label wire:click="$dispatch('modal.open', {component: 'web.modal.fatura-modal'})"
                       class="bg-base-100 p-4 rounded-lg cursor-pointer border border-gray-600 flex items-center gap-4">
                    <input type="radio" wire:model.live="paymentMethod" value="havale"
                           class="radio radio-primary hidden"/>
                    <!-- Heroicon -->
                    <x-icon name="tabler.paperclip"/>
                    <!-- Metin -->
                    <div class="flex-1">
                    <span
                        class="text-lg font-medium">{{ $fatura != null ? 'Vergi/TCKN: ' . $fatura : 'Fatura bilgisi seçin' }}</span>
                    </div>
                </label>
                @if ($type == \App\Enum\PaymentType::cart)
                    <label
                        class="bg-base-100 p-4 rounded-lg cursor-pointer border border-gray-600 flex items-center gap-4">
                        <input type="radio" wire:model.live="paymentMethod" value="havale"
                               class="radio radio-primary hidden"/>
                        <x-icon name="o-gift"/>

                        <div class="flex-1">
                            <span class="text-lg font-medium">Kupon Kullan</span>
                        </div>

                    </label>
                @endif
            </div>


            <!-- Sabit Alt Kısım -->
            <div class="p-4 border-t border-gray-200 bg-base-100 fixed bottom-0 left-0 w-full max-w-md box-border">


                <div class="flex justify-between text-sm">
                    <p>Ara Toplam</p>
                    <p class="font-medium">@price($this->calculateAraToplam())</p>
                </div>
                <div class="flex justify-between text-sm">
                    <p>Vergi</p>
                    <p class="font-medium">@price($this->calculateVergi())</p>
                </div>
                @if ($this->calculateDiscount() > 0)
                    <div class="flex justify-between text-sm">
                        <p>İndirim</p>
                        <p class="font-medium">@price($this->calculateDiscount() * -1)</p>
                    </div>
                @endif
                <div class="flex justify-between text-sm font-bold">
                    <p>Toplam</p>
                    <p>@price($this->calculateTotalAndKomisyon())</p>
                </div>
                @if ($selectedMethod == 1)
                    <x-button icon="tabler.lock" label="ÖDEMEYİ TAMAMLA"
                              wire:click="creditCardCheckout"
                              class="btn btn-primary w-full py-2 mt-4"/>
                @else
                    <x-button icon="tabler.lock" label="{{ $this->calculateTotalAndKomisyon() }} TL - ÖDEME YAPTIM"
                              wire:click="submitHavale"
                              class="btn btn-primary w-full py-2 mt-4"/>
                @endif

            </div>
        @endif


    </div>
</div>
