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
                    DANIŞAN OLUŞTURMA<br />{{ $approve->user->name }} -
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
                            <p class="font-medium">Adı:</p>
                            <p class="">'BAKIRKÖY' - </p>
                        </div>
                        <div>
                            <p class="font-medium">Telefon:</p>
                            <p class=""></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Email:</p>
                            <p class=""></p>
                        </div>
                        <div>
                            <p class="font-medium">Cinsiyet:</p>
                            <p class="">

                            </p>
                        </div>
                    </div>

                    <!-- Ek Bilgiler -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Doğum Tarihi:</p>
                            <p class=""></p>
                        </div>
                        <div>
                            <p class="font-medium">Yetki:</p>
                            <p class=""></p>
                        </div>
                    </div>

                    <!-- Yeni Eklenen Bilgiler -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Adres:</p>
                            <p class=""></p>
                        </div>
                        <div>
                            <p class="font-medium">TCKimlik:</p>
                            <p class=""></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Ülke:</p>
                            <p class=""></p>
                        </div>
                        <div>
                            <p class="font-medium">İl:</p>
                            <p class=""></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">İlçe:</p>
                            <p class=""></p>
                        </div>
                        <div>
                            <p class="font-medium">SMS Gönderme:</p>
                            <p class=""></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Giriş Yapabilme:</p>
                            <p class=""></p>
                        </div>
                    </div>

                    <!-- Mesaj Girişi -->
                    <div>
                        <x-textarea placeholder="Mesajınızı girin..."></x-textarea>
                    </div>
                </div>

                <!-- Kart Alt Bilgi -->
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
