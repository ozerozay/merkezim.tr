<div>
    <x-header title="{{ __('client.menu_seans') }}" separator progress-indicator>
        <x-slot:actions>
            @if ($add_seans)
                <x-button class="btn-primary btn-sm" link="{{ route('client.shop.packages') }}" icon="o-plus">
                    {{ __('client.page_seans_add_seans') }}
                </x-button>
            @endif
        </x-slot:actions>
    </x-header>
    <div class="p-4 bg-base-100 dark:bg-base-200 rounded-lg shadow-md mb-6">
        <!-- Özet Bölümü -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Toplam Hizmetler -->
            <div class="flex flex-col items-center bg-blue-50 dark:bg-blue-900 rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Toplam Hizmet</p>
                <p class="text-lg font-bold text-blue-800 dark:text-blue-300">{{ $data->count() }}</p>
            </div>

            <!-- Devam Eden Hizmetler -->
            <div class="flex flex-col items-center bg-green-50 dark:bg-green-900 rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Devam Eden</p>
                <p class="text-lg font-bold text-green-800 dark:text-green-300">{{ $data->where('remaining', '>', 0)->count() }}</p>
            </div>

            <!-- Tamamlanan Hizmetler -->
            <div class="flex flex-col items-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Tamamlanan</p>
                <p class="text-lg font-bold text-gray-800 dark:text-gray-300">{{ $data->where('remaining', 0)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Devam Eden Hizmetler -->
        @foreach ($data->where('remaining', '>', 0) as $service)
            @php
                $remaining_percentage = ($service->remaining / $service->total) * 100;

                // Renk ve emoji ayarları
                $cardClass = 'bg-blue-50 dark:bg-blue-900'; // Devam edenler için renk
                $textClass = 'text-blue-800 dark:text-blue-300'; // Devam edenler için yazı rengi
            @endphp

            <x-card
                title="🌟 {{ $service->service->name }}"
                separator
                class="mb-2 card w-full {{ $cardClass }} shadow-md hover:shadow-lg transition">

                <!-- İlerleme Durumu -->
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Tamamlama Durumu</p>
                    <x-progress value="{{ number_format($remaining_percentage) }}" max="100"
                                class="progress-success h-2 mt-1"/>
                </div>

                <!-- Kategori Bilgisi -->
                <div class="mb-2">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kategori</p>
                        <p class="text-sm {{ $textClass }} font-bold">{{ $service->service->category->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- Kalan ve Toplam -->
                <div class="mb-2">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kalan</p>
                        <p class="text-sm {{ $textClass }} font-bold">{{ $service->remaining }}</p>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Toplam</p>
                        <p class="text-sm {{ $textClass }} font-bold">{{ $service->total }}</p>
                    </div>
                </div>
            </x-card>
        @endforeach

        <!-- Tamamlanan Hizmetler Başlık -->
        @if ($data->where('remaining', 0)->count() > 0)
            <div class="col-span-full mt-6">
                <h2 class="text-lg font-bold text-gray-700 dark:text-gray-300">Tamamlanan Hizmetler</h2>
            </div>
        @endif

        <!-- Tamamlanan Hizmetler -->
        @foreach ($data->where('remaining', 0) as $service)
            @php
                // Renk ve emoji ayarları
                $cardClass = 'bg-green-50 dark:bg-green-900 opacity-75'; // Tamamlananlar için renk
                $textClass = 'text-green-800 dark:text-green-300'; // Tamamlananlar için yazı rengi
            @endphp

            <x-card
                title="✅ {{ $service->service->name }}"
                separator
                class="mb-2 card w-full {{ $cardClass }} shadow-md hover:shadow-lg transition">

                <!-- Kategori Bilgisi -->
                <div class="mb-2">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Kategori</p>
                        <p class="text-sm {{ $textClass }} font-bold">{{ $service->service->category->name ?? '-' }}</p>
                    </div>
                </div>

                <!-- Toplam Bilgi -->
                <div class="mb-2">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Toplam</p>
                        <p class="text-sm {{ $textClass }} font-bold">{{ $service->total }}</p>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>


</div>
