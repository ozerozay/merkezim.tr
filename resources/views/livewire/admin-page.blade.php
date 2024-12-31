<div>
    <x-header title="Anasayfa" subtitle="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" separator>
        <x-slot:actions>
            <x-button class="btn btn-primary" @click="openSettings" icon="tabler.settings">Özelleştir</x-button>
        </x-slot:actions>
    </x-header>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <div class="container mx-auto my-5">
        <div x-data="{
    sections: [
        {
            id: 1,
            title: 'Satış',
            isFavorite: false,
            targetPercentage: 85,
            salesGoal: 10000,
            currentSales: 8500,
            remainingDays: 5
        },
        {
            id: 2,
            title: 'Randevu',
            isFavorite: false,
            targetPercentage: 60,
            appointmentsGoal: 20,
            completedAppointments: 12,
            remainingDays: 10
        },
        {
            id: 3,
            title: 'Tahsilat',
            isFavorite: false,
            targetPercentage: 45,
            collectionGoal: 5000,
            currentCollection: 2250,
            remainingDays: 7
        },
        {
            id: 4,
            title: 'Danışan',
            isFavorite: false,
            targetPercentage: 90,
            consultantGoal: 50,
            currentConsultants: 45,
            remainingDays: 10
        }
    ],
    toggleFavorite(id) {
        const section = this.sections.find(sec => sec.id === id);
        if (section) {
            section.isFavorite = !section.isFavorite;
        }
    }
}" x-init="new Sortable($refs.sortableContainer, { animation: 150, handle: '.drag-handle' })">
            <h2 class="text-3xl font-bold text-gray-900 mt-5 dark:text-gray-100 mb-6 flex items-center space-x-2">
                <span>🏆</span> <span>Hedefler</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-ref="sortableContainer">
                <template x-for="section in sections" :key="section.id">
                    <div class="bg-base-100 rounded-lg p-6 dark:bg-gray-800" :class="{
                'border border-green-500': section.targetPercentage >= 75,
                'border border-yellow-500': section.targetPercentage >= 50 && section.targetPercentage < 75,
                'border border-red-500': section.targetPercentage < 50
            }">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                                <span>🎯</span> <span x-text="section.title"></span>
                            </h3>
                            <div class="flex items-center space-x-2">
                                <div class="drag-handle cursor-move text-gray-500 dark:text-gray-300">
                                    <x-icon name="tabler.drag" class="w-5 h-5"/>
                                </div>
                                <div class="dropdown dropdown-end">
                                    <button tabindex="0" class="btn btn-sm btn-circle btn-outline">
                                        <x-icon name="tabler.settings" class="w-5 h-5"/>
                                    </button>
                                    <ul tabindex="0"
                                        class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-48">
                                        <li><a @click="toggleFavorite(section.id)"
                                               x-text="section.isFavorite ? 'Kaldır' : 'Favorilere Ekle'"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <div class="text-center" x-show="section.salesGoal">
                                <p class="text-sm text-gray-600 dark:text-gray-400">🎯 Hedef</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.salesGoal} ₺`"></p>
                            </div>
                            <div class="text-center" x-show="section.currentSales">
                                <p class="text-sm text-gray-600 dark:text-gray-400">📈 Şu Ana Kadar</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.currentSales} ₺`"></p>
                            </div>
                            <div class="text-center" x-show="section.remainingDays">
                                <p class="text-sm text-gray-600 dark:text-gray-400">⏳ Kalan Gün</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.remainingDays} gün`"></p>
                            </div>
                            <div class="text-center" x-show="section.appointmentsGoal">
                                <p class="text-sm text-gray-600 dark:text-gray-400">🎯 Hedef</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.appointmentsGoal} Randevu`"></p>
                            </div>
                            <div class="text-center" x-show="section.completedAppointments">
                                <p class="text-sm text-gray-600 dark:text-gray-400">📈 Tamamlanan</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.completedAppointments} Randevu`"></p>
                            </div>
                            <div class="text-center" x-show="section.collectionGoal">
                                <p class="text-sm text-gray-600 dark:text-gray-400">🎯 Hedef</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.collectionGoal} ₺`"></p>
                            </div>
                            <div class="text-center" x-show="section.currentCollection">
                                <p class="text-sm text-gray-600 dark:text-gray-400">📥 Toplanan</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.currentCollection} ₺`"></p>
                            </div>
                            <div class="text-center" x-show="section.consultantGoal">
                                <p class="text-sm text-gray-600 dark:text-gray-400">🎯 Hedef</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.consultantGoal} Danışan`"></p>
                            </div>
                            <div class="text-center" x-show="section.currentConsultants">
                                <p class="text-sm text-gray-600 dark:text-gray-400">📋 Tamamlanan</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100"
                                   x-text="`${section.currentConsultants} Danışan`"></p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="font-semibold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                                <span>⭐</span> <span>Başarı Oranı</span>
                            </p>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-2">
                                <div class="h-2.5 rounded-full" :class="{
                            'bg-green-500': section.targetPercentage >= 75,
                            'bg-yellow-500': section.targetPercentage >= 50 && section.targetPercentage < 75,
                            'bg-red-500': section.targetPercentage < 50
                        }" :style="`width: ${section.targetPercentage}%`"></div>
                            </div>
                            <p class="text-right mt-2 text-sm font-bold flex items-center justify-end space-x-2">
                                <span x-text="`${section.targetPercentage}%`"></span> <span>🔥</span>
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>


        <div x-data="{
    sections: [
        { id: 1, title: 'Son Yapılan Kasa İşlemleri', content: `
            <div class='space-y-4'>
                <div>
                    <h4 class='text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2'>Merkez</h4>
                    <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex justify-between items-center'>
                        <div>
                            <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>POS</p>
                            <p class='text-xs text-gray-600 dark:text-gray-400'>Ahmet Yılmaz • 12.03.2024</p>
                        </div>
                        <p class='text-lg font-bold text-green-600 dark:text-green-400'>+500 ₺</p>
                    </div>
                </div>
                <div>
                    <h4 class='text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2'>Şube 2</h4>
                    <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex justify-between items-center'>
                        <div>
                            <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>Nakit</p>
                            <p class='text-xs text-gray-600 dark:text-gray-400'>Ayşe Kaya • 14.03.2024</p>
                        </div>
                        <p class='text-lg font-bold text-red-600 dark:text-red-400'>-200 ₺</p>
                    </div>
                </div>
                <div>
                    <h4 class='text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2'>Şube 3</h4>
                    <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-3 flex justify-between items-center'>
                        <div>
                            <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>Banka</p>
                            <p class='text-xs text-gray-600 dark:text-gray-400'>Mehmet Çelik • 15.03.2024</p>
                        </div>
                        <p class='text-lg font-bold text-green-600 dark:text-green-400'>+1000 ₺</p>
                    </div>
                </div>
            </div>` },
        { id: 2, title: 'Onay Bekleyen İşlemler', content: `
            <div class='space-y-4'>
                <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-4'>
                    <div class='flex justify-between items-center mb-2'>
                        <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>İade Talebi</p>
                        <p class='text-xs text-gray-600 dark:text-gray-400'>Merkez</p>
                    </div>
                    <p class='text-xs text-gray-600 dark:text-gray-400'>Ahmet Yılmaz - 12.03.2024</p>
                    <p class='text-xs text-gray-500 dark:text-gray-500 italic'>Ürün hatalı gönderilmiş, müşteri iade talep ediyor.</p>
                    <div class='flex justify-between mt-4'>
                        <button class='btn btn-sm btn-error'>İptal Et</button>
                        <button class='btn btn-sm btn-success'>Onayla</button>
                    </div>
                </div>
                <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-4'>
                    <div class='flex justify-between items-center mb-2'>
                        <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>Fiyat Revizyonu</p>
                        <p class='text-xs text-gray-600 dark:text-gray-400'>Şube 2</p>
                    </div>
                    <p class='text-xs text-gray-600 dark:text-gray-400'>Ayşe Kaya - 14.03.2024</p>
                    <p class='text-xs text-gray-500 dark:text-gray-500 italic'>Yeni kampanya nedeniyle %10 indirim yapılması isteniyor.</p>
                    <div class='flex justify-between mt-4'>
                        <button class='btn btn-sm btn-error'>İptal Et</button>
                        <button class='btn btn-sm btn-success'>Onayla</button>
                    </div>
                </div>
            </div>` },
        { id: 3, title: 'Bugünün Ajandası', content: `
            <div class='space-y-4'>
                <div>
                    <h4 class='text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2'>Sabah</h4>
                    <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-3'>
                        <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>09:00 - Müşteri Adı: Ahmet Yılmaz</p>
                        <p class='text-xs text-gray-600 dark:text-gray-400'>Hizmet: Saç Kesimi</p>
                    </div>
                    <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-3 mt-2'>
                        <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>10:30 - Müşteri Adı: Ayşe Kaya</p>
                        <p class='text-xs text-gray-600 dark:text-gray-400'>Hizmet: Manikür</p>
                    </div>
                </div>
                <div>
                    <h4 class='text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2'>Öğleden Sonra</h4>
                    <div class='bg-gray-100 dark:bg-gray-700 rounded-lg p-3'>
                        <p class='text-sm font-bold text-gray-900 dark:text-gray-100'>13:00 - Müşteri Adı: Mehmet Çelik</p>
                        <p class='text-xs text-gray-600 dark:text-gray-400'>Hizmet: Cilt Bakımı</p>
                    </div>
                </div>
            </div>` }
    ]
}" x-init="new Sortable($refs.sortableContainer, { animation: 150, handle: '.drag-handle' })">
            <h2 class="text-3xl font-bold text-gray-900 mt-5 dark:text-gray-100 mb-6 flex items-center space-x-2">
                <span>🛠️</span> <span>Yönetim</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-6" x-ref="sortableContainer">
                <template x-for="section in sections" :key="section.id">
                    <div class="bg-base-100 rounded-xl shadow-lg p-6 dark:bg-gray-800">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                                <span>🔧</span> <span x-text="section.title"></span>
                            </h3>
                            <div class="drag-handle cursor-move text-gray-500 dark:text-gray-300">
                                <x-icon name="tabler.drag" class="w-5 h-5"/>
                            </div>
                        </div>
                        <div x-html="section.content"></div>
                    </div>
                </template>
            </div>
        </div>
        <div class="container mx-auto my-10" x-data="setupWizard">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- Adımlar (Kurulum Sihirbazı) -->
                <template x-for="(step, index) in steps" :key="step.id">
                    <div
                        class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all duration-300"
                        :class="{
                    'border-l-4 border-green-500': step.completed,
                    'border-l-4 border-gray-300': !step.completed
                }">
                        <!-- Durum Simgesi ve Başlık -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    <span x-text="step.title"></span>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    <span x-text="step.description"></span>
                                </p>
                            </div>
                            <div class="flex items-center">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full text-white"
                              :class="step.completed ? 'bg-green-500' : 'bg-gray-400'">
                            <span x-text="step.completed ? '✅' : '⏳'"></span>
                        </span>
                            </div>
                        </div>
                        <!-- İşlem Butonu -->
                        <button
                            class="btn btn-primary btn-sm w-full mt-4"
                            :disabled="!steps[index - 1]?.completed && index !== 0"
                            x-on:click="markAsCompleted(step.id)">
                            <span x-text="step.completed ? 'Tamamlandı' : 'Yeni Ekle'"></span>
                        </button>
                        <!-- Oluşturulan Çıktılar -->
                        <div class="mt-6" x-show="step.completed" x-transition>
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">Oluşturulanlar:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mt-2">
                                <template x-for="item in step.outputs" :key="item">
                                    <li x-text="item"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('setupWizard', () => ({
                    steps: [
                        {
                            id: 1,
                            title: 'Hizmet Kategorileri',
                            description: 'Kategorilerinizi oluşturun ve yönetin.',
                            completed: false,
                            outputs: ['Kategori 1', 'Kategori 2', 'Kategori 3']
                        },
                        {
                            id: 2,
                            title: 'Hizmetler',
                            description: 'Hizmetlerinizi tanımlayın.',
                            completed: false,
                            outputs: ['Hizmet 1', 'Hizmet 2', 'Hizmet 3']
                        },
                        {
                            id: 3,
                            title: 'Hizmet Odaları',
                            description: 'Odalarınızı ekleyin.',
                            completed: false,
                            outputs: ['Oda 1', 'Oda 2']
                        },
                        {
                            id: 4,
                            title: 'Kasalar',
                            description: 'Kasalarınızı yönetin.',
                            completed: false,
                            outputs: ['Kasa 1', 'Kasa 2', 'Kasa 3']
                        },
                    ],
                    markAsCompleted(id) {
                        const step = this.steps.find(s => s.id === id);
                        if (step) {
                            step.completed = true;
                        }
                    },
                }));
            });
        </script>


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
                            <li>+ Online Tahsilat Alma, Teklif Satın Alma, Değerlendirme ve Bahşiş,Kendi Paketini
                                Oluşturma,
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
