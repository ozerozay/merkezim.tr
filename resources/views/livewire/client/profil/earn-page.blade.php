<div class="relative text-base-content p-2 min-h-[200px]">
    <!-- Loading Indicator -->
    <div wire:loading class="absolute inset-0 bg-base-200/50 backdrop-blur-sm rounded-lg z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <div class="flex flex-col items-center gap-2">
                <span class="loading loading-spinner loading-md text-primary"></span>
                <span class="text-sm text-base-content/70">Yükleniyor...</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="h-full flex flex-col">
        <!-- Header Section with Stats -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <i class="text-2xl text-primary">🎉</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Kullandıkça Kazan</h2>
                        <p class="text-sm text-base-content/70">10 hizmet tamamladığınızda ödül kazanın!</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-primary">
                        <i class="text-2xl">📊</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Toplam Hizmet</div>
                    <div class="stat-value text-lg">23</div>
                </div>
                
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-success">
                        <i class="text-2xl">🎁</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Kazanılan Ödül</div>
                    <div class="stat-value text-lg">1</div>
                </div>

                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-warning">
                        <i class="text-2xl">⭐️</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Kalan Hizmet</div>
                    <div class="stat-value text-lg">7</div>
                </div>
            </div>
        </div>

        <!-- Hizmet Durumu Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-primary/10 rounded-lg">
                    <i class="text-primary text-lg">📈</i>
                </div>
                <h3 class="font-medium">Hizmetleriniz</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Cilt Bakımı -->
                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="text-lg">✨</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium">Cilt Bakımı</h4>
                            <p class="text-sm text-base-content/70">Tamamlanan: 8 / 10</p>
                        </div>
                    </div>
                    <div class="w-full bg-base-200 rounded-full h-2 mb-3">
                        <div class="bg-primary h-2 rounded-full transition-all" style="width: 80%"></div>
                    </div>
                    <button class="btn btn-sm btn-disabled w-full">Devam Edin</button>
                </div>

                <!-- Epilasyon -->
                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-success/10 flex items-center justify-center">
                            <span class="text-lg">⚡️</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium">Epilasyon</h4>
                            <p class="text-sm text-base-content/70">Tamamlanan: 10 / 10</p>
                        </div>
                    </div>
                    <div class="w-full bg-base-200 rounded-full h-2 mb-3">
                        <div class="bg-success h-2 rounded-full transition-all" style="width: 100%"></div>
                    </div>
                    <button class="btn btn-sm btn-primary w-full">Ödül Talep Et</button>
                </div>

                <!-- Masaj -->
                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-warning/10 flex items-center justify-center">
                            <span class="text-lg">🌟</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium">Masaj</h4>
                            <p class="text-sm text-base-content/70">Tamamlanan: 5 / 10</p>
                        </div>
                    </div>
                    <div class="w-full bg-base-200 rounded-full h-2 mb-3">
                        <div class="bg-warning h-2 rounded-full transition-all" style="width: 50%"></div>
                    </div>
                    <button class="btn btn-sm btn-disabled w-full">Devam Edin</button>
                </div>
            </div>
        </div>

        <!-- Nasıl Çalışır Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-warning/10 rounded-lg">
                    <i class="text-warning text-lg">💡</i>
                </div>
                <h3 class="font-medium">Nasıl Çalışır?</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="text-lg">1️⃣</span>
                        </div>
                        <h4 class="font-medium">Hizmet Al</h4>
                    </div>
                    <p class="text-sm text-base-content/70">Herhangi bir kategoriden hizmet alın.</p>
                </div>

                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="text-lg">2️⃣</span>
                        </div>
                        <h4 class="font-medium">Tamamla</h4>
                    </div>
                    <p class="text-sm text-base-content/70">10 hizmet tamamlayın.</p>
                </div>

                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="text-lg">3️⃣</span>
                        </div>
                        <h4 class="font-medium">Kazan</h4>
                    </div>
                    <p class="text-sm text-base-content/70">Ödülünüzü talep edin ve kazanın!</p>
                </div>
            </div>
        </div>
    </div>
</div>
