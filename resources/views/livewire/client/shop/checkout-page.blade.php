<div>
    <div class="fixed inset-y-0 right-0 w-full max-w-md shadow-lg flex flex-col h-screen  bg-base-100">
        <!-- Başlık -->
        <div class="p-4 border-b border-base-200 flex justify-between items-center">
            <h1 class="text-lg font-semibold">Ödeme Yap</h1>
            <x-button icon="tabler.x" class="btn-sm btn-outline text-gray-600 ml-auto"
                wire:click="$dispatch('slide-over.close')" />
        </div>
        <div class="flex-1 overflow-y-auto p-4 space-y-4 ">
            @if ($paymentMethod == 'kk')
                <div class="bg-base-100 border border-gray-600 p-4 rounded-lg flex items-center gap-4">
                    <form wire:submit.prevent="processPayment">
                        <div class="form-control mb-4">
                            <x-input label="Kartın Üzerinde Yazan İsim" wire:model="cardName" icon="o-user" />
                        </div>
                        <div class="form-control mb-4">
                            <x-input inputmode="numeric" label="Kredi Kartı Numaranız" wire:model="cardNumber"
                                icon="o-credit-card" placeholder="9999-9999-9999-9999" maxLength="19"
                                hint="Taksit seçenekleri için kart numaranızı girin" x-mask="9999-9999-9999-9999" />
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="form-control mb-4">
                                <x-input inputmode="numeric" placeholder="GG/AA" label="Son Kullanma Tarihi"
                                    wire:model="expiryDate" maxLength="5" icon="o-calendar-days" x-mask="99/99" />
                            </div>
                            <div class="form-control mb-4">
                                <x-input inputmode="numeric" maxLength="3" placeholder="CVV" label="CVV"
                                    wire:model="cvv" icon="o-credit-card" x-mask="999" />
                            </div>
                        </div>
                        <img src="{{ asset('/bankalar-tek-parca.jpg') }}" />
                    </form>
                </div>
                <div class="bg-base-100 p-4 border border-gray-600 rounded-lg">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <span class="label-text text-bold">PEŞİN</span>
                            <input type="radio" name="radio-10" class="radio checked:bg-red-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                        <label class="label cursor-pointer">
                            <span class="label-text">1 TAKSİT <i>+@price(149)</i></span>
                            <input type="radio" name="radio-10" class="radio checked:bg-blue-500" checked="checked" />
                        </label>
                    </div>
                </div>
                <x-checkbox label="Ön bilgilendirme ve mesafeli satış sözleşmesini kabul ediyorum."
                    wire:model="item1" />
            @endif
            @if ($paymentMethod == 'havale')
                <x-input label="MARGE YAZILIM LİMİTED ŞİRKETİ" readonly value="MARGE YAZILIM LİMİTED ŞİRKETİ">
                    <x-slot:append>
                        <x-button label="Kopyala" icon="o-check" class="btn-primary rounded-s-none" />
                    </x-slot:append>
                </x-input>
                <x-hr />
                <x-input label="TÜRKİYE İŞ BANKASI" readonly value="TR5456456465156">
                    <x-slot:append>
                        <x-button label="Kopyala" icon="o-check" class="btn-primary rounded-s-none" />
                    </x-slot:append>
                </x-input>
                <x-input label="GARANTİ BANKASI" readonly value="TR5456456465156">
                    <x-slot:append>
                        <x-button label="Kopyala" icon="o-check" class="btn-primary rounded-s-none" />
                    </x-slot:append>
                </x-input>
            @endif
            <label class="bg-base-100 p-4 rounded-lg cursor-pointer border border-gray-600 flex items-center gap-4">
                <input type="radio" wire:model.live="paymentMethod" value="havale"
                    class="radio radio-primary hidden" />
                <!-- Heroicon -->
                <x-icon name="tabler.paperclip" />
                <!-- Metin -->
                <div class="flex-1">
                    <span class="text-lg font-medium">Fatura bilgisi seçin</span>
                </div>
            </label>
            <label class="bg-base-100 p-4 rounded-lg cursor-pointer border border-gray-600 flex items-center gap-4">
                <input type="radio" wire:model.live="paymentMethod" value="havale"
                    class="radio radio-primary hidden" />
                <!-- Heroicon -->
                <x-icon name="o-gift" />
                <!-- Metin -->
                <div class="flex-1">
                    <span class="text-lg font-medium">Kupon Kullan</span>
                </div>
            </label>
        </div>


        <!-- Sabit Alt Kısım -->
        <div class="p-4 border-t border-gray-200 bg-base-100">
            <div class="flex justify-between text-sm">
                <p>Ara Toplam</p>
                <p class="font-medium">@price(1000)</p>
            </div>
            <div class="flex justify-between text-sm">
                <p>Vergi</p>
                <p class="font-medium">@price(5000)</p>
            </div>
            <div class="flex justify-between text-sm">
                <p>İndirim</p>
                <p class="font-medium">@price(-5000)</p>
            </div>
            <div class="flex justify-between text-sm font-bold">
                <p>Toplam</p>
                <p>@price(60000)</p>
            </div>
            <x-button label="Ödememi Yaptım - 4579tl"
                wire:click="$dispatch('slide-over.open',
                {component: 'login.login-page'})"
                class="btn btn-primary w-full py-2 mt-4" />

        </div>
    </div>


</div>
