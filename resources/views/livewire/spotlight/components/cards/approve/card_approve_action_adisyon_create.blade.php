<?php

new class extends \Livewire\Volt\Component {
    use \App\Traits\ApproveTrait;
};

?>
<div class="mx-auto mt-2">
    <x-collapse>
        <x-slot:heading>
            <div class="card-header border-b border-gray-300">
                <h2 class="text-sm font-semibold">
                    ADİSYON OLUŞTURMA<br />{{ $approve->user->name }} -
                    {{ $approve->created_at->format('d/m/Y H:i:s') }}
                </h2>
            </div>
        </x-slot:heading>
        <x-slot:content>
            <div class="card bg-base-200 shadow-md rounded-lg">
                <div class="card-body p-2 space-y-2 text-sm">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Ad Soyad:</p>
                            <p>{{ \App\Models\User::select('id', 'name')->where('id', $approve->data['client_id'])->first()?->name }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Mesaj:</p>
                            <p>{{ $approve->message }}</p>
                        </div>
                        <div>
                            <p class="font-medium">Tarih:</p>
                            <p>{{ \App\Peren::parseDateAndFormat($approve->data['date']) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-medium">Fiyat:</p>
                            <p>@price($approve->data['price'])</p>
                        </div>
                        <div>
                            <p class="font-medium">Kupon Fiyatı:</p>
                            <p>@price($approve->data['coupon_price'])</p>
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
