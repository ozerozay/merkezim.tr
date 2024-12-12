<?php

new class extends \Livewire\Volt\Component
{
    public $approve;

    public $message = '';

    public function mount(\App\Models\Approve $approve)
    {
        $this->approve = $approve;
    }

    public function ok()
    {
        $this->dispatch('approve-ok', $this->approve->id, $this->message);
    }

    public function cancel()
    {
        $this->dispatch('approve-cancel', $this->approve->id, $this->message);
    }
};

?>

<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header p-2 border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    {{ $approve->user->name }}<br />'Yeni adisyon oluşturma yetkisi'<br />11/12/2024
                </h2>
            </div>
        </x-slot:heading>
        <x-slot:content>
            <div class="card bg-base-200 shadow-md rounded-lg">
                <!-- Kart İçerik -->
                <div class="card-body p-2 space-y-2 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Müşteri ID:</p>
                            <p>1</p>
                        </div>
                        <div>
                            <p class="font-medium">Kullanıcı ID:</p>
                            <p>1</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Mesaj:</p>
                            <p>sdfgsdf</p>
                        </div>
                        <div>
                            <p class="font-medium">Tarih:</p>
                            <p>2024-12-11</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Fiyat:</p>
                            <p>481.11</p>
                        </div>
                        <div>
                            <p class="font-medium">Kupon Fiyatı:</p>
                            <p>0</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <p class="font-medium">Yetki:</p>
                            <p>action_adisyon_create</p>
                        </div>
                    </div>

                    <!-- Ödemeler -->
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Ödemeler:</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="card bg-base-100 border border-gray-200 p-2 rounded">
                                <h4 class="font-medium">Ödeme ID: 1</h4>
                                <p><strong>Tarih:</strong> 2024-12-11</p>
                                <p><strong>Fiyat:</strong> 481.11</p>
                                <p><strong>Kasa:</strong> İŞ BANKASI</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hizmetler -->
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Hizmetler:</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="card bg-base-100 border border-gray-200 p-2 rounded">
                                <h4 class="font-medium">Hizmet: GENİTAL</h4>
                                <p><strong>Tür:</strong> Service</p>
                                <p><strong>Fiyat:</strong> 150</p>
                                <p><strong>Adet:</strong> 1</p>
                            </div>
                            <div class="card bg-base-100 border border-gray-200 p-2 rounded">
                                <h4 class="font-medium">Hizmet: ALT BACAK</h4>
                                <p><strong>Tür:</strong> Service</p>
                                <p><strong>Fiyat:</strong> 150</p>
                                <p><strong>Adet:</strong> 1</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personel -->
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold mb-2">Personel:</h3>
                        <p>Personel ID: 1</p>
                    </div>
                </div>
                <div>
                        <x-textarea wire:model="message" placeholder="Mesajınızı girin..."></x-textarea>
                    </div>
                <!-- Kart Alt Bilgi -->
                <div class="card-footer p-2 border-t border-gray-300 flex justify-between items-center">

                    <button wire:click="cancel" class="btn btn-outline">İptal Et</button>
                    <button wire:click="ok" class="btn btn-primary">Onayla</button>
                </div>

            </div>
        </x-slot:content>
    </x-collapse>
</div>
