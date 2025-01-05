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
                        <i class="text-2xl text-primary">🎁</i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">{{ __('client.menu_referans') }}</h2>
                        <p class="text-sm text-base-content/70">{{ __('client.page_referans_subtitle') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-primary">
                        <i class="text-2xl">👥</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Toplam Davet</div>
                    <div class="stat-value text-lg">0</div>
                </div>
                
                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-success">
                        <i class="text-2xl">✨</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Kazanılan Ödül</div>
                    <div class="stat-value text-lg">0</div>
                </div>

                <div class="stat bg-base-200/50 rounded-xl p-4">
                    <div class="stat-figure text-warning">
                        <i class="text-2xl">⭐️</i>
                    </div>
                    <div class="stat-title text-xs opacity-70">Aktif Davet</div>
                    <div class="stat-value text-lg">0</div>
                </div>
            </div>
        </div>

        <!-- Davet Linki Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-primary/10 rounded-lg">
                    <i class="text-primary text-lg">🔗</i>
                </div>
                <h3 class="font-medium">Davet Linkiniz</h3>
            </div>

            <div class="w-full" x-data="{
                link: '{{ route('client.index', ['ref' => auth()->user()->unique_id]) }}',
                copied: false,
                timeout: null,
                copy() {
                    $clipboard(this.link);
                    this.copied = true;
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        this.copied = false;
                    }, 3000);
                }
            }">
                <div class="flex items-center gap-2">
                    <input type="text" x-bind:value="link" readonly
                           class="input input-bordered w-full text-sm bg-base-200/50"/>
                    <button x-on:click="copy" 
                            class="btn btn-primary"
                            :class="{ 'btn-success': copied }">
                        <span x-show="!copied">Kopyala</span>
                        <span x-show="copied">Kopyalandı!</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Paylaşım Yöntemleri Section -->
        <div class="bg-base-100 rounded-xl shadow-sm border border-base-200 p-4 mb-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-success/10 rounded-lg">
                    <i class="text-success text-lg">📱</i>
                </div>
                <h3 class="font-medium">Hızlı Paylaşım</h3>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="sms://?body={{ route('client.index', ['ref' => auth()->user()->unique_id]) }}"
                   class="btn btn-outline flex-1 gap-2">
                    <i class="text-xl">💬</i> SMS ile Paylaş
                </a>
                <a href="whatsapp://send?text={{ route('client.index', ['ref' => auth()->user()->unique_id]) }}"
                   class="btn btn-outline flex-1 gap-2">
                    <i class="text-xl">📱</i> WhatsApp ile Paylaş
                </a>
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
                        <h4 class="font-medium">Davet Et</h4>
                    </div>
                    <p class="text-sm text-base-content/70">Davetiye linkini arkadaşlarınla paylaş.</p>
                </div>

                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="text-lg">2️⃣</span>
                        </div>
                        <h4 class="font-medium">Kayıt</h4>
                    </div>
                    <p class="text-sm text-base-content/70">Davet ettiğin kişi sisteme kayıt olsun.</p>
                </div>

                <div class="card bg-base-200/30 p-4 rounded-xl">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="text-lg">3️⃣</span>
                        </div>
                        <h4 class="font-medium">Kazan</h4>
                    </div>
                    <p class="text-sm text-base-content/70">Her başarılı davet için ödüller kazan!</p>
                </div>
            </div>
        </div>
    </div>
</div>