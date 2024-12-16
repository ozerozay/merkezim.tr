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
                    SATIŞ<br />{{ $approve->user->name }} -
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
                            <p class="font-medium">Tarih:</p>
                            <p>2024-12-12</p>
                        </div>
                        <div>
                            <p class="font-medium">Tutar:</p>
                            <p>1650 TL</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Satış Türü ID:</p>
                            <p>1</p>
                        </div>
                        <div>
                            <p class="font-medium">Yetki:</p>
                            <p>action_client_sale</p>
                        </div>
                    </div>

                    <!-- Nakit Bilgileri -->
                    <div class="grid grid-cols-1 gap-2">
                        <p class="font-medium">Nakit:</p>
                        <ul class="list-disc list-inside">
                            <li>12/12/2024 - 100 TL - İŞ BANKASI</li>
                        </ul>
                    </div>

                    <!-- Taksit Bilgileri -->
                    <div class="grid grid-cols-1 gap-2">
                        <p class="font-medium">Taksit:</p>
                        <ul class="list-disc list-inside">
                            <li>12/12/2024 - 310.00 TL</li>
                            <li>12/01/2025 - 310.00 TL</li>
                            <li>12/02/2025 - 310.00 TL</li>
                            <li>12/03/2025 - 310.00 TL</li>
                            <li>12/04/2025 - 310 TL</li>
                        </ul>
                    </div>

                    <!-- Hizmet Bilgileri -->
                    <div class="grid grid-cols-1 gap-2">
                        <p class="font-medium">Hizmetler:</p>
                        <ul class="list-disc list-inside">
                            <li>ALT BACAK - 150 TL</li>
                            <li>ASDSAD - 1500 TL</li>
                        </ul>
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
