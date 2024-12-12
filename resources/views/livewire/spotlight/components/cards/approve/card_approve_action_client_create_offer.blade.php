<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    Kullanıcı Bilgileri<br />'Yeni teklif oluşturma yetkisi'<br />30/08/2018 14:32:13
                </h2>
            </div>
        </x-slot:heading>
        <x-slot:content>
            <div class="card bg-base-200 shadow-md rounded-lg">
                <!-- Kart İçerik -->
                <div class="card-body p-2 space-y-2 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Müşteri ID:</p>
                            <p>1</p>
                        </div>
                        <div>
                            <p class="font-medium">Toplam Fiyat:</p>
                            <p>150.00 TL</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Mesaj:</p>
                            <p>asdasd</p>
                        </div>
                        <div>
                            <p class="font-medium">Son Geçerlilik Tarihi:</p>
                            <p>Belirtilmedi</p>
                        </div>
                    </div>

                    <!-- Hizmetler Kartları -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        <div class="card bg-base-100 border border-gray-200 p-2 rounded">
                            <h3 class="text-lg font-semibold">GENİTAL</h3>
                            <p class="text-sm">Tür: Hizmet</p>
                            <p class="text-sm">Fiyat: <span class="font-semibold">150 TL</span></p>
                            <p class="text-sm">Adet: <span class="font-semibold">1</span></p>
                            <p class="text-sm">Hediye: <span class="font-semibold">Hayır</span></p>
                            <p class="text-sm">Hizmet ID: <span class="font-semibold">2</span></p>
                        </div>
                        <div class="card bg-base-100 border border-gray-200 p-2 rounded">
                            <h3 class="text-lg font-semibold">ALT BACAK</h3>
                            <p class="text-sm">Tür: Hizmet</p>
                            <p class="text-sm">Fiyat: <span class="font-semibold">150 TL</span></p>
                            <p class="text-sm">Adet: <span class="font-semibold">1</span></p>
                            <p class="text-sm">Hediye: <span class="font-semibold">Hayır</span></p>
                            <p class="text-sm">Hizmet ID: <span class="font-semibold">7</span></p>
                        </div>
                        <div class="card bg-base-100 border border-gray-200 p-2 rounded">
                            <h3 class="text-lg font-semibold">EPİLASYON TÜM VÜCUT</h3>
                            <p class="text-sm">Tür: Paket</p>
                            <p class="text-sm">Fiyat: <span class="font-semibold">1000 TL</span></p>
                            <p class="text-sm">Adet: <span class="font-semibold">1</span></p>
                            <p class="text-sm">Hediye: <span class="font-semibold">Hayır</span></p>
                            <p class="text-sm">Paket ID: <span class="font-semibold">1</span></p>
                        </div>
                    </div>
                </div>

                <!-- Kart Alt Bilgi -->
                <div class="card-footer p-2 border-t border-gray-300 flex justify-between items-center">
                    <button class="btn btn-outline">İptal Et</button>
                    <button class="btn btn-primary">Onayla</button>
                </div>
            </div>
        </x-slot:content>
    </x-collapse>
</div>
