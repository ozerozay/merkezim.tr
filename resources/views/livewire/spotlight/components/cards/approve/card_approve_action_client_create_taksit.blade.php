<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    Taksit Bilgileri<br />'Taksit oluşturma yetkisi'<br />12/12/2024
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
                            <p>asdasd</p>
                        </div>
                        <div>
                            <p class="font-medium">Satış ID:</p>
                            <p>12</p>
                        </div>
                    </div>

                    <!-- Taksit Bilgileri -->
                    <div class="grid grid-cols-1 gap-2">
                        <p class="font-medium">Taksitler:</p>
                        <ul class="list-disc list-inside">
                            <li>12/12/2024 - 150.00 TL
                                <ul class="list-circle list-inside ml-5">
                                    <li>ALT BACAK (Service ID: 7, Quantity: 1)</li>
                                </ul>
                            </li>
                            <li>12/01/2025 - 150.00 TL</li>
                            <li>12/02/2025 - 150.00 TL</li>
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
