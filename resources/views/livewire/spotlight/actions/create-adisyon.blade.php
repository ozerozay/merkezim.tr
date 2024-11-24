<div>
    <x-slide-over title="Adisyon Oluştur" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.form.date_time
            wire:key="date-fieeeeld-{{ Str::random(10) }}"
            label="Tarih"
            wire:model="date"
        />
        <livewire:components.form.staff_multi_dropdown wire:key="mdasdf-{{ Str::random(10) }}" wire:model="staff_ids"/>
        <x-input label="Adisyon Tutarı" hint="Boş bırakırsanız seçtiğiniz hizmet ve ürün toplamı hesaplanır."
                 wire:model="price" suffix="₺" money/>
        <x-input label="Açıklama" wire:model="message"/>
        <x-hr/>
        <div class="grid grid-cols-2 gap-2">
            <x-button
                class="btn-outline"
                icon="tabler.plus"
                wire:click="$dispatch('modal.open', {component: 'modals.select-service-modal', arguments: {'client': {{ $client->id }}}})">
                Hizmet Ekle
            </x-button>
            <x-button
                class="btn-outline"
                icon="tabler.plus"
                wire:click="$dispatch('modal.open', {component: 'modals.select-package-modal', arguments: {'client': {{ $client->id }}}})">
                Paket Ekle
            </x-button>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <x-button
                class="btn-outline"
                icon="tabler.plus"
                wire:click="$dispatch('modal.open', {component: 'modals.select-product-modal', arguments: {'client': {{ $client->id }}}})">
                Ürün Ekle
            </x-button>
            <x-button
                class="btn-outline"
                icon="tabler.plus"
                wire:click="$dispatch('modal.open', {component: 'modals.select-payment-modal', arguments: {'client': {{ $client->id }}}})">
                Peşinat Ekle
            </x-button>
        </div>
        @foreach ($selected_services as $service)
            <x-list-item :item="$service" no-separator no-hover>
                @if ($service['gift'])
                    <x-slot:avatar>
                        <x-badge value="H" class="badge-primary"/>
                    </x-slot:avatar>
                @endif
                <x-slot:value>
                    {{ $service['name'] }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $service['quantity'] }} seans - @price($service['price'])
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                              wire:click="deleteItem({{ $service['id'] }}, '{{ $service['type'] }}')" spinner/>
                </x-slot:actions>
            </x-list-item>
        @endforeach
        @foreach ($selected_payments as $payment)
            <x-list-item :item="$payment" no-separator no-hover>
                <x-slot:value>
                    {{ $payment['kasa_name'] }}
                </x-slot:value>
                <x-slot:sub-value>
                    @price($payment['price'])
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                              wire:click="deletePayment({{ $payment['id'] }})" spinner/>
                </x-slot:actions>
            </x-list-item>
        @endforeach
        @if($selected_services->isNotEmpty())
            <div class="grid grid-cols-2 gap-2">
                <x-button class="btn-sm">
                    Toplam:
                    <x-badge class="badge-neutral">
                        <x-slot:value>
                            @price($this->totalPrice())
                        </x-slot:value>
                    </x-badge>
                </x-button>
                <x-button class="btn-sm">
                    Hediye:
                    <x-badge class="badge-neutral">
                        <x-slot:value>
                            @price($this->totalGift())
                        </x-slot:value>
                    </x-badge>
                </x-button>
            </div>
        @endif
    </x-slide-over>
</div>
