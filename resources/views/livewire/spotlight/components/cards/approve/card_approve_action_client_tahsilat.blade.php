<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    Tahsilat Bilgileri<br />'Tahsilat yetkisi'<br />12/12/2024
                </h2>
            </div>
        </x-slot:heading>
        <x-slot:content>
            <div class="card bg-base-200 shadow-md rounded-lg">
                <!-- Kart İçerik -->
                <div class="card-body p-2 space-y-2 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Tarih:</p>
                            <p>12/12/2024</p>
                        </div>
                        <div>
                            <p class="font-medium">Tutar:</p>
                            <p>150.00 TL</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Kasa ID:</p>
                            <p>1</p>
                        </div>
                        <div>
                            <p class="font-medium">Kullanıcı ID:</p>
                            <p>1</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Müşteri ID:</p>
                            <p>1</p>
                        </div>
                        <div>
                            <p class="font-medium">Yetki:</p>
                            <p>action_client_tahsilat</p>
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
