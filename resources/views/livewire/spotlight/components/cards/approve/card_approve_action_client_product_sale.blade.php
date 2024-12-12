<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    Ürün Satış Bilgileri<br />'Ürün satış yetkisi'<br />12/12/2024
                </h2>
            </div>
        </x-slot:heading>
        <x-slot:content>
            <div class="card bg-base-200 shadow-md rounded-lg">
                <!-- Kart İçerik -->
                <div class="card-body p-2 space-y-2 text-sm">
                    <!-- Genel Bilgiler -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Mesaj:</p>
                            <p>sadfsadf</p>
                        </div>
                        <div>
                            <p class="font-medium">Tarih:</p>
                            <p>2024-12-12</p>
                        </div>
                    </div>

                    <!-- Ödeme Bilgileri -->
                    <div class="grid grid-cols-1 gap-2">
                        <p class="font-medium">Ödemeler:</p>
                        <ul class="list-disc list-inside">
                            <li>2024-12-12 - 250.00 TL (Kasa: İŞ BANKASI)</li>
                        </ul>
                    </div>

                    <!-- Ürün Bilgileri -->
                    <div class="grid grid-cols-1 gap-2">
                        <p class="font-medium">Ürünler:</p>
                        <ul class="list-disc list-inside">
                            <li>ASDASD (Product ID: 1, Quantity: 1, Price: 21 TL)</li>
                        </ul>
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
