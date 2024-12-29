<div>
    <div>
        <x-slide-over title="Randevu Bilgileri">
            <div class="card bg-base-200 shadow-md p-6 w-full max-w-lg mx-auto">
                <h2 class="text-xl font-bold mb-4 text-center">Randevu Bilgileri</h2>
                <div class="flex flex-col gap-2 mb-6">
                    <p><strong>Şube:</strong> {{ $appointment->branch->name }}</p>
                    <p><strong>Tarih:</strong> {{ $appointment->date->format('d/m/Y') }}</p>
                    <p><strong>Durum:</strong> {{ $appointment->status->label() }}</p>
                    <p><strong>Saat Başlangıç - Bitiş:</strong> {{ $appointment->date_start->format('H:i') }}
                        - {{ $appointment->date_end->format('H:i') }}</p>
                    <p><strong>Hizmetler:</strong> {{ $appointment->getServiceNamesPublic() }}</p>
                    <p>{{ $remaining_text }}</p>
                </div>
            </div>
            @if ($can_cancel)
                <div class="card bg-base-200 shadow-md p-6 w-full max-w-lg mx-auto">
                    <h2 class="text-xl font-bold mb-4 text-center">İptal Talebi</h2>
                    <div class="flex flex-col gap-2 mb-6">
                        <x-textarea label="İptal nedeni"/>
                        <x-button type="submit" spinner="save" class="btn-primary btn-block" icon="tabler.send">
                            Gönder
                        </x-button>
                    </div>
                </div>
            @endif
            <x-slot:actions>

            </x-slot:actions>
        </x-slide-over>
    </div>
</div>
