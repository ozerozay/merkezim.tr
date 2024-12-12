<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    <br />'Yeni müşteri oluşturma yetkisi'<br />30/08/2018
                    14:32:13
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
                            <p class=""></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Yetki:</p>
                            <p class="">
                            </p>
                        </div>
                    </div>

                    <!-- Mesaj Girişi -->
                    <div>
                        <x-textarea placeholder="Mesajınızı girin..."></x-textarea>
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
