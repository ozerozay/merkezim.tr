<div>
    <x-card title="Şube Ayarları" subtitle="Şubelerinize özel ayarları yapılandırın" separator progress-indicator>
        <!-- Şube Seçimi - Birden fazla şube varsa göster -->
        @if(count($branches) > 1)
            <div class="mb-6">
                <label class="label">
                    <span class="label-text font-semibold">Şube Seçimi</span>
                </label>
                <x-select 
                    wire:model.live="branch_id"
                    :options="$branches"
                    option-label="name"
                    option-value="id"
                    placeholder="Şube seçiniz"
                />

                <!-- Şube Ayarlarını Kopyalama - Birden fazla şube varsa göster -->
                @if(count($branches) >1)
                    <div class="mt-4 p-4 bg-base-200 rounded-lg">
                        <h4 class="font-semibold mb-3">Şube Ayarlarını Kopyala</h4>
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label class="label">
                                    <span class="label-text">Kaynak Şube</span>
                                </label>
                                <x-select 
                                    wire:model="copy_from_branch_id"
                                    :options="collect($branches)->filter(fn($branch) => $branch['id'] != $branch_id)->values()"
                                    option-label="name"
                                    option-value="id"
                                    placeholder="Kaynak şubeyi seçin"
                                />
                            </div>
                            <x-button 
                                type="button"
                                class="btn-primary"
                                wire:click="copySettings"
                                wire:confirm="Seçili şubenin ayarları hedef şubeye kopyalanacak. Devam etmek istiyor musunuz?"
                            >
                                Ayarları Kopyala
                            </x-button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($branch_id)
            <x-form wire:submit="save">
                <div class="space-y-6">
                    <!-- Sayfa Görünürlük Ayarları -->
                    <div class="card bg-base-200 p-4 rounded-lg">
                        <h3 class="font-semibold mb-4">Sayfa Görünürlük Ayarları</h3>
                        <div class="space-y-3">
                            <x-checkbox label="Kullandıkça Kazan" wire:model="client_page_earn"/>
                            <x-checkbox label="Randevu Sayfası" wire:model="client_page_appointment"/>
                            <x-checkbox label="Teklif Sayfası" wire:model="client_page_offer"/>
                            <x-checkbox label="Seans Sayfası" wire:model="client_page_seans"/>
                            <x-checkbox label="Taksit Sayfası" wire:model="client_page_taksit"/>
                            <x-checkbox label="Kupon Sayfası" wire:model="client_page_coupon"/>
                            <x-checkbox label="Referans Sayfası" wire:model="client_page_referans"/>
                            <x-checkbox label="Paket Sayfası" wire:model="client_page_package"/>
                            <x-checkbox label="Fatura Sayfası" wire:model="client_page_fatura"/>
                            <x-checkbox label="Destek Sayfası" wire:model="client_page_support"/>
                        </div>
                    </div>

                    <!-- Seans Ayarları -->
                    <div class="card bg-base-200 p-4 rounded-lg">
                        <h3 class="font-semibold mb-4">Seans Ayarları</h3>
                        <div class="space-y-3">
                            <x-checkbox label="Bitmiş Seansları Göster" wire:model="client_page_seans_show_zero"/>
                            <x-checkbox label="Kategori Göster" wire:model="client_page_seans_show_category"/>
                            <x-checkbox label="Seans Ekle Butonu" wire:model="client_page_seans_add_seans"/>
                            <x-checkbox label="İstatistikleri Göster" wire:model="client_page_seans_show_stats"/>
                        </div>
                    </div>

                    <!-- Randevu Ayarları -->
                    <div class="card bg-base-200 p-4 rounded-lg">
                        <h3 class="font-semibold mb-4">Randevu Ayarları</h3>
                        <div class="space-y-4">
                            <x-checkbox label="Hizmetleri Göster" wire:model="client_page_appointment_show_services"/>
                            <x-checkbox label="İstatistikleri Göster" wire:model="client_page_appointment_show_stats"/>
                            <x-checkbox label="Kategori Seçimi Zorunlu" wire:model="client_page_appointment_create_once_category"/>
                            <x-checkbox label="Randevu Onayı Gerekli" wire:model="client_page_appointment_create_appointment_approve"/>
                            <x-checkbox label="Geç Ödemeye İzin Ver" wire:model="client_page_appointment_create_appointment_late_payment"/>
                            
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Randevu İptal Süresi (Saat)</span>
                                </label>
                                <x-input type="number" wire:model="client_page_appointment_cancel_time"/>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Randevu Oluşturma Yöntemleri</span>
                                </label>
                                <x-choices :options="$appointment_create_methods" wire:model="client_page_appointment_create"/>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Randevu Durumları</span>
                                </label>
                                <x-choices :options="$appointment_statuses" wire:model="client_page_appointment_show"/>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Randevu Oluşturulabilecek Şubeler</span>
                                </label>
                                <x-choices :options="$appointment_create_branches" wire:model="client_page_appointment_create_branches"/>
                            </div>
                        </div>
                    </div>

                    <!-- Taksit Ayarları -->
                    <div class="card bg-base-200 p-4 rounded-lg">
                        <h3 class="font-semibold mb-4">Taksit Ayarları</h3>
                        <div class="space-y-3">
                            <x-checkbox label="Taksit Ödeme" wire:model="client_page_taksit_pay"/>
                            <x-checkbox label="Sıfır Bakiyeleri Göster" wire:model="client_page_taksit_show_zero"/>
                            <x-checkbox label="Kilitli Taksitleri Göster" wire:model="client_page_taksit_show_locked"/>
                        </div>
                    </div>

                    <!-- Ödeme Ayarları -->
                    <div class="card bg-base-200 p-4 rounded-lg">
                        <h3 class="font-semibold mb-4">Ödeme Ayarları</h3>
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Ödeme Yöntemleri</span>
                                </label>
                                <x-choices :options="$payment_types" wire:model="client_payment_types"/>
                            </div>

                            <!-- KDV Ayarları -->
                            <div class="grid gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Taksit KDV Oranı (%)</span>
                                    </label>
                                    <x-input type="number" wire:model="payment_taksit_include_kdv"/>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Bahşiş KDV Oranı (%)</span>
                                    </label>
                                    <x-input type="number" wire:model="payment_tip_include_kdv"/>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Teklif KDV Oranı (%)</span>
                                    </label>
                                    <x-input type="number" wire:model="payment_offer_include_kdv"/>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Mağaza KDV Oranı (%)</span>
                                    </label>
                                    <x-input type="number" wire:model="client_page_shop_include_kdv"/>
                                </div>
                            </div>

                            <!-- Komisyon Ayarları -->
                            <div class="grid gap-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Taksit Komisyon Oranı (%)</span>
                                    </label>
                                    <x-input type="number" step="0.01" wire:model="payment_taksit_include_komisyon"/>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Bahşiş Komisyon Oranı (%)</span>
                                    </label>
                                    <x-input type="number" step="0.01" wire:model="payment_tip_include_komisyon"/>
                                </div>

                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Teklif Komisyon Oranı (%)</span>
                                    </label>
                                    <x-input type="number" step="0.01" wire:model="payment_offer_include_komisyon"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kaydet Butonu -->
                <div class="mt-6 flex justify-end">
                    <x-button type="submit" class="btn-primary" spinner="save">
                        Ayarları Kaydet
                    </x-button>
                </div>
            </x-form>
        @else
            <div class="text-center py-8 text-base-content/70">
                Lütfen ayarlarını düzenlemek istediğiniz şubeyi seçin.
            </div>
        @endif
    </x-card>
</div>
