<div>
    <x-header title="{{ __('client.menu_referans') }}" subtitle="{{ __('client.page_referans_subtitle') }}" separator
              progress-indicator>
    </x-header>
    <div class="flex flex-col items-center gap-6 p-6 bg-base-100 text-center">
        <!-- Başlık ve Açıklama -->
        <div>
            <h1 class="text-2xl font-bold text-primary">Davet Et, Kazan!</h1>
            <p class="text-sm text-gray-500 mt-2">
                Arkadaşlarını davet et ve her yeni kayıt için ödüller kazan!
            </p>
        </div>

        <!-- Davet Linki -->
        <div class="w-full max-w-lg" x-data="{
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
                <input
                    type="text"
                    x-bind:value="link"
                    readonly
                    class="input input-bordered flex-grow text-sm"
                />
                <button x-on:click="copy" class="btn btn-primary">
                    <span x-show="!copied">Kopyala</span>
                    <span x-show="copied">Kopyalandı!</span>
                </button>
            </div>
        </div>

        <!-- Paylaşım Yöntemleri -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a
                href="sms://?body={{ route('client.index', ['ref' => auth()->user()->unique_id]) }}"
                class="btn btn-outline btn-sm flex items-center gap-2">
                <i class="tabler-icon tabler-device-mobile-message"></i> SMS ile Paylaş
            </a>
            <a
                href="whatsapp://send?text={{ route('client.index', ['ref' => auth()->user()->unique_id]) }}"
                class="btn btn-outline btn-sm flex items-center gap-2">
                <i class="tabler-icon tabler-brand-whatsapp"></i> Whatsapp ile Paylaş
            </a>
        </div>

        <!-- Ödül Açıklaması -->
        <div class="card bg-base-200 shadow-md p-4 w-full max-w-lg text-left">
            <h2 class="text-lg font-semibold text-primary">Nasıl Çalışır?</h2>
            <ul class="list-disc list-inside text-sm text-gray-600 mt-2">
                <li>Davetiye linkini paylaş.</li>
                <li>Davet edilen kişi kayıt olsun.</li>
                <li>Her yeni kayıt için ödüller kazan!</li>
            </ul>
        </div>
    </div>

</div>
