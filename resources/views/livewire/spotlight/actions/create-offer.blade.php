<div>
    <x-slide-over title="Teklif Oluştur" subtitle="{{ $client->name ?? '' }}">
        <x-input label="Teklif Tutarı" wire:model="price" suffix="₺" money/>
        <x-input label="Açıklama" wire:model="message"/>
        <div class="grid grid-cols-2 gap-1">
            <livewire:components.form.date_time
                label="Son Geçerlilik Tarihi (Sınırsız ise boş bırakın)"
                wire:model="expire_date"
                wire:key="date-field-{{ Str::random(10) }}"
                minDate="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"/>

            <livewire:components.form.number_dropdown
                wire:key="number-field-{{ Str::random(10) }}"
                label="Paket Kullanım Süresi (Sınırsız ise 0)"
                includeZero="true" suffix="Ay"
                wire:model="month"
            />
        </div>
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
