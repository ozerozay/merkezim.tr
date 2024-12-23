<div>
    <x-slide-over title="Değerlendir ve Bahşiş" subtitle="Fikirleriniz bizim için çok önemli.">
        <div class="card bg-base-200 shadow-md p-6 w-full max-w-lg mx-auto">
            <h2 class="text-xl font-bold mb-4 text-center">Randevu Bilgileri</h2>
            <div class="flex flex-col gap-2 mb-6">
                <p><strong>Personel:</strong> {{ $appointment->finish_user->name ?? '' }}</p>
                <p><strong>Tarih:</strong> {{ $appointment->date->format('d/m/Y') }}</p>
                <p><strong>Saat Başlangıç - Bitiş:</strong> {{ $appointment->date_start->format('H:i') }}
                    - {{ $appointment->date_end->format('H:i') }}</p>
                <p><strong>Hizmetler:</strong> {{ $appointment->finish_service_names_public($appointment) }}</p>
            </div>

            <hr class="my-4 border-gray-400">

            <div class="flex flex-col items-center mb-6">
                <h3 class="text-lg font-semibold">Puanlama</h3>
                <x-rating wire:model="rank" class="bg-yellow-500 rating-lg mt-3"/>
            </div>

            <hr class="my-4 border-gray-400">

            <div class="flex flex-col items-center">
                <h3 class="text-lg font-semibold mb-3">Bahşiş Vermek İster misiniz?</h3>
                <div x-data="{ customAmount: '' }" class="flex gap-4">
                    
                    <x-button class="btn btn-circle btn-outline bg-base-300"
                              @click="customAmount = (10.00).toFixed(2)">10₺
                    </x-button>
                    <x-button class="btn btn-circle btn-outline bg-base-300"
                              @click="customAmount = (20.00).toFixed(2)">20₺
                    </x-button>
                    <x-button class="btn btn-circle btn-outline bg-base-300"
                              @click="customAmount = (50.00).toFixed(2)">50₺
                    </x-button>
                    <div class="form-control">
                        <x-input
                            wire:model="customAmount"
                            x-model="customAmount"
                            placeholder="Kendi Miktarınız"
                            suffix="₺"
                            locale="tr-TR"
                            money
                        />
                    </div>
                </div>

            </div>
        </div>
    </x-slide-over>
</div>
