<div>
    <x-card title="Ayarlar" subtitle="Size özel yazılım" separator progress-indicator>
        <div>
            <x-form id="settingsForm" wire:submit="save">
                <div class="grid grid-cols-1 gap-4">
                    <!-- Boolean Settings -->
                    <div class="form-control">
                        <x-checkbox label="Kullandıkça Kazan Sayfasını Göster" wire:model="client_page_earn"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Randevu Sayfasını Göster" wire:model="client_page_appointment"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Teklif Sayfasını Göster" wire:model="client_page_offer"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Seans Sayfasını Göster" wire:model="client_page_seans"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Kupon Sayfasını Göster" wire:model="client_page_coupon"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Fatura Sayfasını Göster" wire:model="client_page_fatura"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Taksit Sayfasını Göster" wire:model="client_page_taksit"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Paket Sayfasını Göster" wire:model="client_page_package"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Destek Sayfasını Göster" wire:model="client_page_support"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Referans Sayfasını Göster" wire:model="client_page_referans"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Taksit ödemeleri yapılabilir mi?" wire:model="client_page_taksit_pay"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Müşteriler teklif isteyebilirler mi?"
                                    wire:model="client_page_offer_request"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Randevu sayfasında hizmet detayları gözüksün mü ?"
                                    wire:model="client_page_appointment_show_services"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Müşterileriniz bir hizmet kategorisinden 1 adet mi randevu alabilir ?"
                                    wire:model="client_page_appointment_create_once_category"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Müşterileriniz aldığı randevu hemen onaylansın mı ?"
                                    wire:model="client_page_appointment_create_appointment_approve"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Gecikmiş ödemesi olan müşterileriniz randevu alabilir mi ?"
                                    wire:model="client_page_appointment_create_appointment_late_payment"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Hizmet sayfasında seans yükle butonu olsun mu?"
                                    wire:model="client_page_seans_add_seans"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Hizmet sayfasında seansı bitmiş hizmetler gözüksün mü?"
                                    wire:model="client_page_seans_show_zero"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox
                            label="Hizmet sayfasında kalan seanslar tek bir hizmet adı altında kategorize edilsin mi ?"
                            wire:model="client_page_seans_show_category"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Taksit sayfasında ödemesi bitmiş hizmetler gözüksün mü?"
                                    wire:model="client_page_taksit_show_zero"/>
                    </div>

                    <div class="form-control">
                        <x-checkbox label="Taksit sayfasında taksite ait kilitli hizmetler gözüksün mü ?"
                                    wire:model="client_page_taksit_show_locked"/>
                    </div>


                    <x-hr/>


                    <!-- Select Dropdown Settings -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Ödeme Yöntemleri</span>
                        </label>
                        <x-choices :options="$payment_types" wire:model="client_payment_types"/>
                    </div>

                    <!-- Numeric Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Taksitlerde KDV (%) (0 - KDV'siz)</span>
                        </label>
                        <x-input type="number" class="input input-bordered" min="0"
                                 max="99" wire:model="payment_taksit_include_kdv"/>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Taksitlerde Kredi Kartı Komisyonu (%) (0 - Komisyonsuz)</span>
                        </label>
                        <x-input class="input input-bordered" money wire:model="payment_taksit_include_komisyon"/>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Bahşişlerde KDV (%) (0 - KDV'siz)</span>
                        </label>
                        <x-input type="number" class="input input-bordered" min="0"
                                 max="99" wire:model="payment_tip_include_kdv"/>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Bahşişlerde Kredi Kartı Komisyonu (%) (0 - Komisyonsuz)</span>
                        </label>
                        <x-input class="input input-bordered" money wire:model="payment_tip_include_komisyon"/>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tekliflerde KDV (%) (0 - KDV'siz)</span>
                        </label>
                        <x-input type="number" class="input input-bordered" min="0"
                                 max="99" wire:model="payment_offer_include_kdv"/>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tekliflerde Kredi Kartı Komisyonu (%) (0 - Komisyonsuz)</span>
                        </label>
                        <x-input class="input input-bordered" money wire:model="payment_offer_include_komisyon"/>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Mağazada KDV (%)</span>
                        </label>
                        <x-input type="number" class="input input-bordered" min="0"
                                 max="99" wire:model="client_page_shop_include_kdv"/>
                    </div>

                    <!-- Multi-Checkbox Settings -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Randevu Durumları</span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <x-choices :options="$appointment_statuses" wire:model="client_page_appointment_show"/>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span
                                class="label-text">Randevu kaç dakika kalınca iptal edilemez ? (0 iptal edilemez)</span>
                        </label>
                        <x-input type="number" class="input input-bordered" min="0"
                                 wire:model="client_page_appointment_cancel_time"/>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Oluşturma Metotları</span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <x-choices :options="$appointment_create_methods"
                                       wire:model="client_page_appointment_create"/>
                        </div>
                    </div>

                    <!-- Branches Setting -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Randevu Şubeleri</span>
                        </label>
                        <x-choices :options="$appointment_create_branches"
                                   wire:model="client_page_appointment_create_branches"/>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <x-button type="submit" class="btn btn-primary" spinner="save" wire:click="save">Kaydet</x-button>
                </div>
            </x-form>
        </div>

    </x-card>

</div>
