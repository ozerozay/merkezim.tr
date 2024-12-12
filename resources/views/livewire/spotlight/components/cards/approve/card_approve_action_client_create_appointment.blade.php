<div class="mx-auto mt-2">

    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    Kullanıcı Bilgileri<br />'Yeni randevu oluşturma yetkisi'<br />18/12/2024 12:00
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
                            <p class="font-medium">Kullanıcı ID:</p>
                            <p>1</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Mesaj:</p>
                            <p>asdfasdf</p>
                        </div>
                        <div>
                            <p class="font-medium">Tarih:</p>
                            <p>2024-12-18 12:00</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Oda ID:</p>
                            <p>1</p>
                        </div>
                        <div>
                            <p class="font-medium">Kategori ID:</p>
                            <p>1</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="font-medium">Yetki:</p>
                            <p>action_client_create_appointment</p>
                        </div>
                    </div>

                    <!-- Hizmetler -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        <div class="card bg-base-100 border border-gray-200 p-2 rounded">
                            <h3 class="text-lg font-semibold">Hizmet ID: 31</h3>
                            <p class="text-sm">Hizmet detayı mevcut değil.</p>
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
