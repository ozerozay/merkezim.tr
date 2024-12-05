<div>
    <x-slide-over title="Satış" subtitle="{{ $client->name ?? '' }}">
        <div x-data="{ current: @entangle('step') }">
            <div class="steps w-full justify-center">
                <div class="step {{ $step > 0 ? 'step-primary' : '' }} flex-1">Satış</div>
                <div class="step {{ $step > 1 ? 'step-primary' : '' }} flex-1">Hizmet</div>
                <div class="step {{ $step > 2 ? 'step-primary' : '' }} flex-1">Taksit</div>
                <div class="step {{ $step > 3 ? 'step-primary' : '' }} flex-1">Bitti</div>
            </div>
            <div x-show="current == '1'" class="p-2">
                <livewire:components.form.sale_type_dropdown wire:key="std-{{ Str::random() }}"
                    wire:model="sale_type_id" />
                <livewire:components.form.date_time label="Tarih" wire:model="date"
                    wire:key="date-field-{{ Str::random(10) }}" />
                <livewire:components.form.staff_multi_dropdown wire:key="smdmd-{{ Str::random() }}"
                    wire:model="staff_ids" />
                @can(\App\Enum\PermissionType::change_sale_price)
                    <x-input label="Satış Tutarı (Tutarı değiştirmek istemiyorsanız 0 bırakın.)"
                        wire:model.live.debounce.500ms="price" suffix="₺" money />
                @endcan
                <livewire:components.form.number_dropdown wire:key="nnsdf-{{ Str::random() }}" suffix="AY"
                    label="Paket Kullanım Süresi (Ay) - 0 sınırsız" includeZero="true" wire:model="expire_date" />
                <x-input label="Satış notunuz" wire:model="message" />

            </div>

            <div x-show="current == '2'" class="p-2">
                <livewire:spotlight.components.add-product-coupon-service-package wire:key="cxvbf-{{ Str::random() }}"
                    :client="$client" :selected_services="$selected_services" :selected_payments="$selected_payments" :price="$price" :couponPrice="$couponPrice"
                    :actives="['coupon', 'service', 'package', 'payment']" />
            </div>

            <div x-show="current == '3'" class="p-2">
                @if ($selected_taksits->isEmpty())
                    Yapılandırılacak Tutar: {{ $this->remainingPayment() }}
                    <livewire:components.form.date_time label="Tarih" wire:model="firstDate"
                        wire:key="datew-field-{{ Str::random(10) }}" />
                    <livewire:components.form.number_dropdown wire:key="nnxsdf-{{ Str::random() }}"
                        label="Taksit Sayısı" wire:model="taksitSayisi" />
                    <x-button label="Taksit Oluştur" wire:click="taksitlendir" class="btn-outline mt-2 w-full block" />
                @else
                    @foreach ($selected_taksits as $taksit)
                        <x-list-item :item="$taksit" no-separator no-hover>
                            <x-slot:avatar>
                                {{ $taksit['id'] + 1 }}
                            </x-slot:avatar>
                            <x-slot:value>
                                @price($taksit['price'])
                            </x-slot:value>
                            <x-slot:sub-value>
                                {{ $taksit['date'] }}
                            </x-slot:sub-value>
                            <x-slot:actions>
                                <x-button icon="o-pencil" class="btn-outline btn-sm" wire:click="deleteTaksits()"
                                    spinner />

                            </x-slot:actions>
                        </x-list-item>
                    @endforeach
                    <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                        wire:click="deleteTaksits()" spinner />
                @endif
            </div>
            <div x-show="current == '4'" class="p-2">
                Satış oluşturuldu.
            </div>
        </div>

        @if ($step < 4)
            <x-slot:actions>
                <div class="flex w-full mt-5">
                    @if ($step > 1)
                        <div class="w-1/4">
                            <x-button wire:click="back" icon="o-arrow-uturn-left" label="Geri" class="btn-outline" />
                        </div>
                    @endif
                    <div class="w-3/4"> <x-button wire:click="next" icon="o-arrow-uturn-right" :label="$nextLabel"
                            class="w-full btn-outline" /></div>
                </div>
            </x-slot:actions>
        @else
        @endif
    </x-slide-over>
</div>
