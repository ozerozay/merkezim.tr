<div>
    <x-header title="Anasayfa" subtitle="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" separator progress-indicator />
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
</div>
