<?php

new class extends \Livewire\Volt\Component {
    use \App\Traits\ApproveTrait;
};

?>
<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    MAHSUP<br />{{ $approve->user->name }} -
                    {{ $approve->created_at->format('d/m/Y H:i:s') }}
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
                            <p></p> <!-- dynamically rendered message -->
                        </div>
                        <div>
                            <p class="font-medium">Tarih:</p>
                            <p>{{ date('Y-m-d', strtotime('2024-12-12')) }}</p> <!-- dynamically rendered date -->
                        </div>
                    </div>

                    <!-- Ödeme Bilgileri -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Tutar:</p>
                            <p>{{ number_format(100, 2) }} TL</p> <!-- dynamically rendered price -->
                        </div>
                        <div>
                            <p class="font-medium">Çıkış Kasa ID:</p>
                            <p>1</p> <!-- dynamically rendered 'cikis_kasa_id' -->
                        </div>
                        <div>
                            <p class="font-medium">Giriş Kasa ID:</p>
                            <p>2</p> <!-- dynamically rendered 'giris_kasa_id' -->
                        </div>
                    </div>
                </div>
                <x-form class="p-2 border-t border-gray-300">
                    <x-textarea wire:model="message" placeholder="Açıklama" />
                    <div class="card-footer  flex items-center justify-between">
                        <x-button wire:click="submitReject" class="btn btn-outline btn-error" icon="tabler.x">İptal
                            Et</x-button>
                        <x-button wire:click="submitApprove" class="btn btn-success"
                            icon="tabler.check">Onayla</x-button>
                    </div>
                </x-form>
            </div>
        </x-slot:content>
    </x-collapse>
</div>