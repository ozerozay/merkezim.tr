<div>
    <x-header title="Anasayfa" subtitle="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" separator>
        <x-slot:actions>
            <x-button class="btn btn-primary" @click="openSettings" icon="tabler.settings">Özelleştir</x-button>
        </x-slot:actions>
    </x-header>
    <div class="container mx-auto my-5">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center space-x-2">
            <span>🏆</span> <span>Hedefler</span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-base-100 rounded-lg p-6 dark:bg-gray-800" x-data="{
        isFavorite: false,
        targetPercentage: 85,
        toggleFavorite() {
            this.isFavorite = !this.isFavorite;
        }
    }" :class="{
        'border border-green-500': targetPercentage >= 75,
        'border border-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
        'border border-red-500': targetPercentage < 50
    }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Satış</h3>
                    <div class="dropdown dropdown-end">
                        <button tabindex="0" class="btn btn-sm btn-circle btn-outline">
                            <x-icon name="tabler.settings" class="w-5 h-5"/>
                        </button>
                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-48">
                            <li><a @click="toggleFavorite"
                                   x-text="isFavorite ? 'Remove from Favorites' : 'Add to Favorites'"></a></li>
                        </ul>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Satış tutarına göre aylık hedef</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Başarı:</span>
                    <span :class="{
                'text-green-600 dark:text-green-400': targetPercentage >= 75,
                'text-yellow-600 dark:text-yellow-400': targetPercentage >= 50 && targetPercentage < 75,
                'text-red-600 dark:text-red-400': targetPercentage < 50
            }" x-text="`${targetPercentage}%`"></span>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full" :class="{
                    'bg-green-500': targetPercentage >= 75,
                    'bg-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
                    'bg-red-500': targetPercentage < 50
                }" :style="`width: ${targetPercentage}%`"></div>
                    </div>
                </div>
            </div>

            <div class="bg-base-100 rounded-lg p-6 dark:bg-gray-800" x-data="{
        isFavorite: false,
        targetPercentage: 60,
        toggleFavorite() {
            this.isFavorite = !this.isFavorite;
        }
    }" :class="{
        'border border-green-500': targetPercentage >= 75,
        'border border-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
        'border border-red-500': targetPercentage < 50
    }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Randevu</h3>
                    <div class="dropdown dropdown-end">
                        <button tabindex="0" class="btn btn-sm btn-circle btn-outline">
                            <x-icon name="tabler.settings" class="w-5 h-5"/>
                        </button>
                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-48">
                            <li><a @click="toggleFavorite"
                                   x-text="isFavorite ? 'Remove from Favorites' : 'Add to Favorites'"></a></li>
                        </ul>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Tamamlanan randevu sayısına göre aylık hedef</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Başarı:</span>
                    <span :class="{
                'text-green-600 dark:text-green-400': targetPercentage >= 75,
                'text-yellow-600 dark:text-yellow-400': targetPercentage >= 50 && targetPercentage < 75,
                'text-red-600 dark:text-red-400': targetPercentage < 50
            }" x-text="`${targetPercentage}%`"></span>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full" :class="{
                    'bg-green-500': targetPercentage >= 75,
                    'bg-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
                    'bg-red-500': targetPercentage < 50
                }" :style="`width: ${targetPercentage}%`"></div>
                    </div>
                </div>
            </div>

            <div class="bg-base-100 rounded-lg p-6 dark:bg-gray-800" x-data="{
        isFavorite: false,
        targetPercentage: 45,
        toggleFavorite() {
            this.isFavorite = !this.isFavorite;
        }
    }" :class="{
        'border border-green-500': targetPercentage >= 75,
        'border border-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
        'border border-red-500': targetPercentage < 50
    }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Tahsilat</h3>
                    <div class="dropdown dropdown-end">
                        <button tabindex="0" class="btn btn-sm btn-circle btn-outline">
                            <x-icon name="tabler.settings" class="w-5 h-5"/>
                        </button>
                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-48">
                            <li><a @click="toggleFavorite"
                                   x-text="isFavorite ? 'Remove from Favorites' : 'Add to Favorites'"></a></li>
                        </ul>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Aylık alınan tahsilat</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Başarı:</span>
                    <span :class="{
                'text-green-600 dark:text-green-400': targetPercentage >= 75,
                'text-yellow-600 dark:text-yellow-400': targetPercentage >= 50 && targetPercentage < 75,
                'text-red-600 dark:text-red-400': targetPercentage < 50
            }" x-text="`${targetPercentage}%`"></span>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full" :class="{
                    'bg-green-500': targetPercentage >= 75,
                    'bg-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
                    'bg-red-500': targetPercentage < 50
                }" :style="`width: ${targetPercentage}%`"></div>
                    </div>
                </div>
            </div>

            <div class="bg-base-100 rounded-lg p-6 dark:bg-gray-800" x-data="{
        isFavorite: false,
        targetPercentage: 90,
        toggleFavorite() {
            this.isFavorite = !this.isFavorite;
        }
    }" :class="{
        'border border-green-500': targetPercentage >= 75,
        'border border-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
        'border border-red-500': targetPercentage < 50
    }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Danışan</h3>
                    <div class="dropdown dropdown-end">
                        <button tabindex="0" class="btn btn-sm btn-circle btn-outline">
                            <x-icon name="tabler.settings" class="w-5 h-5"/>
                        </button>
                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-48">
                            <li><a @click="toggleFavorite"
                                   x-text="isFavorite ? 'Remove from Favorites' : 'Add to Favorites'"></a></li>
                        </ul>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Aylık yeni kayıt olan danışan sayısına göre</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Başarı:</span>
                    <span :class="{
                'text-green-600 dark:text-green-400': targetPercentage >= 75,
                'text-yellow-600 dark:text-yellow-400': targetPercentage >= 50 && targetPercentage < 75,
                'text-red-600 dark:text-red-400': targetPercentage < 50
            }" x-text="`${targetPercentage}%`"></span>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full" :class="{
                    'bg-green-500': targetPercentage >= 75,
                    'bg-yellow-500': targetPercentage >= 50 && targetPercentage < 75,
                    'bg-red-500': targetPercentage < 50
                }" :style="`width: ${targetPercentage}%`"></div>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mt-5 dark:text-gray-100 mb-6 flex items-center space-x-2">
            <span>🛠️</span> <span>Widgetlar</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-base-100 rounded-xl shadow-lg p-6 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                        <span>💰</span> <span>Son Yapılan Kasa İşlemleri</span>
                    </h3>
                </div>
                <div
                    class="h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-thumb-rounded scrollbar-track-gray-200 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-700">
                    <div class="space-y-4">
                        <!-- Merkez Şubesi -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Merkez</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">POS</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Ahmet Yılmaz • 12.03.2024</p>
                                </div>
                                <p class="text-lg font-bold" :class="{
                        'text-green-600 dark:text-green-400': true,
                        'text-red-600 dark:text-red-400': false
                    }">+500 ₺</p>
                            </div>
                        </div>

                        <!-- Şube 2 -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Şube 2</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Nakit</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Ayşe Kaya • 14.03.2024</p>
                                </div>
                                <p class="text-lg font-bold" :class="{
                        'text-green-600 dark:text-green-400': false,
                        'text-red-600 dark:text-red-400': true
                    }">-200 ₺</p>
                            </div>
                        </div>

                        <!-- Şube 3 -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Şube 3</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Banka</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Mehmet Çelik • 15.03.2024</p>
                                </div>
                                <p class="text-lg font-bold" :class="{
                        'text-green-600 dark:text-green-400': true,
                        'text-red-600 dark:text-red-400': false
                    }">+1000 ₺</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-base-100 rounded-lg p-6 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                        <span>📋</span> <span>Onay Bekleyen İşlemler</span>
                    </h3>
                    <div class="dropdown dropdown-end">
                        <x-button tabindex="0" class="btn btn-sm btn-outline" icon="tabler.settings" label="İşlemler"
                                  responsive/>
                        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-48">
                            <li><a href="#">Hepsini Onayla</a></li>
                            <li><a href="#">Hepsini İptal Et</a></li>
                            <li><a href="#">Favorilere Ekle</a></li>
                        </ul>
                    </div>
                </div>
                <div
                    class="space-y-4 h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-thumb-rounded scrollbar-track-gray-200 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-700">
                    <div class="flex flex-col bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">İade Talebi</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Merkez</p>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Ahmet Yılmaz - 12.03.2024</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 italic">Ürün hatalı gönderilmiş, müşteri iade
                            talep ediyor.</p>
                        <div class="flex justify-between mt-4">
                            <x-button class="btn btn-sm btn-error" label="İptal Et" icon="tabler.x" responsive/>
                            <x-button class="btn btn-sm btn-success" label="Onayla" icon="tabler.check" responsive/>
                        </div>
                    </div>
                    <div class="flex flex-col bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Fiyat Revizyonu</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Şube 2</p>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Ayşe Kaya - 14.03.2024</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 italic">Yeni kampanya nedeniyle %10 indirim
                            yapılması isteniyor.</p>
                        <div class="flex justify-between mt-4">
                            <x-button class="btn btn-sm btn-error" label="İptal Et" icon="tabler.x" responsive/>
                            <x-button class="btn btn-sm btn-success" label="Onayla" icon="tabler.check" responsive/>
                        </div>
                    </div>
                    <div class="flex flex-col bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Sipariş İptali</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Şube 3</p>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Mehmet Çelik - 15.03.2024</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 italic">Müşteri yanlış ürün sipariş ettiğini
                            belirtti.</p>
                        <div class="flex justify-between mt-4">
                            <x-button class="btn btn-sm btn-error" label="İptal Et" icon="tabler.x" responsive/>
                            <x-button class="btn btn-sm btn-success" label="Onayla" icon="tabler.check" responsive/>
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-base-100 rounded-xl shadow-lg p-6 dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                        <span>📋</span> <span>Bugünün Ajandası</span>
                    </h3>
                </div>
                <div
                    class="h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-thumb-rounded scrollbar-track-gray-200 dark:scrollbar-thumb-gray-600 dark:scrollbar-track-gray-700">
                    <div class="space-y-4">
                        <!-- Sabah Randevuları -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Sabah</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">09:00 - Müşteri Adı: Ahmet
                                    Yılmaz</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Hizmet: Saç Kesimi</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 mt-2">
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">10:30 - Müşteri Adı: Ayşe
                                    Kaya</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Hizmet: Manikür</p>
                            </div>
                        </div>

                        <!-- Öğleden Sonra Randevuları -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Öğleden Sonra</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">13:00 - Müşteri Adı:
                                    Mehmet Çelik</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Hizmet: Cilt Bakımı</p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 mt-2">
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">15:30 - Müşteri Adı: Elif
                                    Şahin</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Hizmet: Masaj</p>
                            </div>
                        </div>

                        <!-- Akşam Randevuları -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Akşam</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">18:00 - Müşteri Adı: Canan
                                    Demir</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Hizmet: Pedikür</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>

    @if (1==2)
        <div class="container mx-auto my-8">
            <h1 class="text-2xl font-bold text-center mb-6">Firma Karşılaştırma Tablosu</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <!-- Firma 1 -->
                <div class="border border-gray-300 bg-base-100 rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-4">MERKEZİM</h2>
                    <h2 class="text-xl font-semibold mb-4">1999 ₺ + KDV</h2>

                    <h3 class="text-lg font-medium mb-2">Yazılım Özellikleri</h3>
                    <ul class="list-disc list-inside">
                        <li>Diğerlerinde bulunan tüm özellikler</li>
                        <li>+ Özelleştirilebilir Ayarlar</li>
                        <li>+ Yazılımı Online Satın Alabilme</li>
                        <li>+ Kurulum Sihirbazı</li>
                        <li>+ Tek ekranda tüm şubeler</li>
                        <li>+ Seans Paylaşma</li>
                        <li>+ Kupon</li>
                        <li>+ Teklif</li>
                        <li>+ Onay (Detaylı)</li>
                        <li>+ Kendi Web Sitesini Oluşturma</li>
                        <li>+ Hizmet Satışı Üzerine Gelişmiş E-Ticaret Sistemi</li>
                        <li>+ Hosting, SSL</li>
                        <li>+ Dil Seçeneği</li>
                        <li>+ Kullan - Kazan (Müdavim)</li>
                        <li>+ Rapor Sonucuna Teklif, Kupon, SMS gönderme</li>
                        <li>+ Özel gün planlayıcısı</li>
                        <li>+ Referans</li>
                        <li>+ Santral</li>
                        <li>+ E-posta Yönetimi</li>
                        <li>+ Kendi Sitesi Üzerinden Çalışma</li>
                        <li>+ Gelişmiş Yapay Zeka (Blog Oluşturma, Çeviri)</li>
                        <li>+ Whatsapp Bot - Hazır komutlar ve kişiselleştirilebilir</li>
                        <li>+ Online Tahsilat Alma, Teklif Satın Alma, Değerlendirme ve Bahşiş,Kendi Paketini Oluşturma,
                            Randevu Oluşturma,
                        </li>
                        <li>+ Tüm bu işlemleri kendi ismiyle kurulan mobil uygulamadan yapabilme (+ Ücret ?)</li>
                        <li>+ Tek tuş ile yönetme</li>
                    </ul>
                </div>

                <!-- Firma 2 -->
                <div class="border border-gray-300 bg-base-100 rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-4">Ixirlife</h2>
                    <h2 class="text-xl font-semibold mb-4">1250₺ + (KDV ?)</h2>
                    <h3 class="text-lg font-medium mb-2">Yazılım Özellikleri</h3>
                    <ul class="list-disc list-inside">
                        <li>Müşteri Yönetimi</li>
                        <li>Randevu Yönetimi</li>
                        <li>Sınırsız Randevu</li>
                        <li>Ürün / Hizmet Yönetimi</li>
                        <li>SMS Yönetimi</li>
                        <li>Mobil Uygulama</li>
                        <li>Online Randevu Sistemi</li>
                        <li>E-posta / Ticket Desteği</li>
                        <li>Yabancı Dil Desteği (EN)</li>
                        <li>Otomatik Hatırlatma SMS</li>
                        <li>Satış / Adisyon Yönetimi</li>
                        <li>Diyetisyen Modülü</li>
                        <li>Şube Yönetimi</li>
                        <li>Telefon Desteği</li>
                        <li>Raporlama Yönetimi</li>
                        <li>Personel Yetkilendirme</li>
                        <li>Toplu SMS Gönderimi</li>
                        <li>Vardiya Yönetimi (Shift)</li>
                        <li>Kasa (Gelir – Gider) Yönetimi</li>
                        <li>Paket Satış Yönetimi</li>
                        <li>Seans Yönetimi</li>
                        <li>Personel Prim ve Ciro Yönetimi</li>
                        <li>Fatura Yönetimi</li>
                        <li>Stok Yönetimi</li>
                        <li>Sınırsız Eğitim</li>
                    </ul>

                </div>

                <!-- Firma 3 -->
                <div class="border border-gray-300 bg-base-100 rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-4">SalonAppy</h2>
                    <h2 class="text-xl font-semibold mb-4">1299₺ + KDV</h2>
                    <h3 class="text-lg font-medium mb-2">Yazılım Özellikleri</h3>
                    <ul class="list-disc list-inside">
                        <li>8 personele kadar</li>
                        <li>Randevu yönetimi</li>
                        <li>Müşteri takibi</li>
                        <li>Online randevu sistemi</li>
                        <li>Avantajlı POS Entegrasyonu</li>
                        <li>+ Özel Müşteri Temsilcisi</li>
                        <li>+ Sınırsız Randevu</li>
                        <li>+ Randevu hatırlatma SMS'leri dahil</li>
                        <li>+ Adisyon yönetimi</li>
                        <li>+ Gelir takibi</li>
                        <li>+ Masraf takibi</li>
                        <li>+ Müşteri parapuan sistemi</li>
                        <li>+ Müşteri memnuniyet SMS'leri</li>
                        <li>+ Gelişmiş personel yetki yönetimi</li>
                        <li>+ E-fatura sistemi</li>
                        <li>+ Avantajlı POS Entegrasyonu</li>
                        <li>+ Özel Müşteri Temsilcisi</li>
                        <li>+ Kendi ismiyle Web Sitesi</li>
                        <li>+ Cari alacak takibi</li>
                        <li>+ Ürün satışı ve stok takibi</li>
                        <li>+ Paket satışı takibi</li>
                        <li>+ Personel prim takibi</li>
                        <li>+ Google Takvim entegrasyonu</li>
                    </ul>
                </div>

                <!-- Firma 4 -->
                <div class="border border-gray-300 bg-base-100 rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-4">Kactaydi</h2>
                    <h2 class="text-xl font-semibold mb-4">1999 ₺ + KDV</h2>
                    <h3 class="text-lg font-medium mb-2">Yazılım Özellikleri</h3>
                    <ul class="list-disc list-inside">
                        <li>Sınırsız Randevu / Aylık</li>
                        <li>Sınırsız Randevu Hatırlatıcı</li>
                        <li>Online Randevu Alınan Personele Hatırlatma</li>
                        <li>Sınırsız Personel</li>
                        <li>Sınırsız Müşteri</li>
                        <li>Online Randevu</li>
                        <li>Gelişmiş Raporlama</li>
                    </ul>

                </div>

                <!-- Firma 5 -->
                <div class="border border-gray-300 bg-base-100 rounded-lg p-4">
                    <h2 class="text-xl font-semibold mb-4">Planla.co</h2>
                    <h2 class="text-xl font-semibold mb-4">1190 ₺ + (KDV ?)</h2>
                    <h3 class="text-lg font-medium mb-2">Yazılım Özellikleri</h3>
                    <ul class="list-disc list-inside">
                        <li>Online Randevu Sistemi</li>
                        <li>Online Ödeme</li>
                        <li>Online Görüşme</li>
                        <li>Sınırsız Randevu Hatırlatıcı</li>
                        <li>Konumunuzu Paylaşın (SMS)</li>
                        <li>Paket Satışı</li>
                        <li>Gelir Gider ve Alacak Takibi</li>
                        <li>Sınırsız Müşteri</li>
                        <li>Sınırsız Personel</li>
                        <li>Personel Yetkilendirme</li>
                        <li>İstatistik Paneli</li>
                        <li>Tekrar Eden Randevular</li>
                        <li>Otomatik Yedekleme</li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="max-w-6xl mx-auto mt-10">
            <h1 class="text-3xl font-semibold text-center mb-8">Kurulum Sihirbazı</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <!-- Hizmet Kategorisi Kartı -->
                <div class="card shadow-md bg-green-100">
                    <div class="card-body">
                        <h2 class="text-lg font-semibold">Hizmet Kategorisi</h2>
                        <p class="text-sm">Eklenen kategoriler</p>
                        <div class="mt-4">
                            <!-- Hizmet Kategorisi Durumu -->
                            <p class="text-green-600">Kategori Eklendi</p>
                        </div>
                    </div>
                </div>

                <!-- Kullanıcı Hesap Kartı -->
                <div class="card shadow-md bg-gray-100">
                    <div class="card-body">
                        <h2 class="text-lg font-semibold">Kullanıcı Hesabı</h2>
                        <p class="text-sm">Hesap bilgilerini tamamlayın</p>
                        <div class="mt-4">
                            <!-- Hesap Durumu -->
                            <p class="text-gray-600">Hesap Oluşturulmadı</p>
                        </div>
                    </div>
                </div>

                <!-- Ödeme Yöntemi Kartı -->
                <div class="card shadow-md bg-green-100">
                    <div class="card-body">
                        <h2 class="text-lg font-semibold">Ödeme Yöntemi</h2>
                        <p class="text-sm">Ödeme yöntemi seçin ve onaylayın</p>
                        <div class="mt-4">
                            <!-- Ödeme Yöntemi Durumu -->
                            <p class="text-green-600">Ödeme Yöntemi Seçildi</p>
                        </div>
                    </div>
                </div>

                <!-- Diğer Adımlar (Benzer şekilde eklenebilir) -->
                <div class="card shadow-md bg-gray-100">
                    <div class="card-body">
                        <h2 class="text-lg font-semibold">Diğer Adımlar</h2>
                        <p class="text-sm">Gerekli diğer adımları tamamlayın</p>
                        <div class="mt-4">
                            <!-- Diğer Adım Durumu -->
                            <p class="text-gray-600">Adım Tamamlanmadı</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- İleri ve Geri Butonları -->
            <div class="flex justify-between mt-8">
                <button class="btn btn-outline">Geri</button>
                <button class="btn btn-primary">İleri</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card separator title="Onay">
                <x-list-item :item="[]" class="cursor-pointer">
                    <x-slot:value>
                        asd
                    </x-slot:value>
                    <x-slot:sub-value>
                        asd
                    </x-slot:sub-value>
                </x-list-item>
            </x-card>
        </div>
    @endif
</div>
