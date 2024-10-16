<?php

use App\Models\User;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?int $client_id;
};
?>
<div>
    <x-header title="Hizmet Satışı" separator>
        <x-slot:actions>
            <x-button label="İptal" icon="tabler.circle-letter-x" @click="window.history.back" class="btn-error" />
            <x-button label="Oluştur" icon="tabler.check" class="btn-success" spinner />
        </x-slot:actions>
    </x-header>

    <div class="grid lg:grid-cols-2 gap-8">
        {{-- CUSTOMER --}}
        <x-card title="Danışan" progress-indicator="updateUser" separator shadow>
            <livewire:components.form.client_dropdown wire:model="client_id" />
        </x-card>

        {{-- SUMMARY --}}
        <x-card title="Hizmet" separator shadow>
            <x-slot:menu>

            </x-slot:menu>

            <div class="grid gap-2">
                <div class="flex gap-3 justify-between items-baseline px-10">
                    <div>Items</div>
                    <div class="border-b border-b-gray-400 border-dashed flex-1"></div>
                    <div class="font-black"></div>
                </div>
                <div class="flex gap-3 justify-between items-baseline px-10">
                    <div>Total</div>
                    <div class="border-b border-b-gray-400 border-dashed flex-1"></div>
                    <div class="font-black"></div>
                </div>
            </div>
        </x-card>
    </div>

    {{-- ITEMS --}}
    <x-card title="Items" separator progress-indicator="updateQuantity" shadow class="mt-8">
        <x-slot:menu>
            {{-- ADD ITEM --}}

        </x-slot:menu>


    </x-card>

    <div class="text-gray-400 text-xs mt-5">
        On this demo you are able to freely modify the order regardless its status.
        The orders goes to a random status after adding an item, just for better display on orders list.
        Of course, you should improve this business logic.
    </div>
</div>