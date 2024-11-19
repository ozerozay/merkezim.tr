<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast, \Livewire\WithPagination, \App\Traits\WithViewPlaceHolder, \Livewire\WithoutUrlPagination;

    public ?int $client;

    public ?int $selected;

    public bool $editing = false;

    public function mount(): void
    {
        $this->sortBy = ['column' => 'created_at', 'direction' => 'asc'];
    }

    public function getData()
    {
        return \App\Actions\Client\GetClientCoupons::run($this->client, true, $this->sortBy);
    }

    public function headers(): array
    {
        return [
            ['key' => 'code', 'label' => 'Kod', 'sortBy' => 'code'],
            ['key' => 'discount_type', 'label' => 'Tip', 'sortBy' => 'discount_type'],
            ['key' => 'count', 'label' => 'Adet', 'sortBy' => 'count'],
            ['key' => 'discount_amount', 'label' => 'İndirim', 'sortBy' => 'discount_amount'],
            ['key' => 'end_date', 'label' => 'Bitiş Tarihi', 'sortable' => false],
            ['key' => 'user.name', 'label' => 'Oluşturan', 'sortBy' => 'user_id'],
        ];
    }

    public function showSettings($id): void
    {

    }

    public function with(): array
    {
        return [
            'coupons' => $this->getData(),
            'headers' => $this->headers()
        ];
    }

};

?>
<div>
    <div class="flex justify-end mb-4 mt-5 gap-2">
        <p>Sıralama işlemlerini tablo görünümünden yapabilirsiniz.</p>
        <x-button wire:click="changeView" label="{{ $view == 'table' ? 'LİSTE' : 'TABLO' }}"
                  icon="{{ $view == 'table' ? 'tabler.list' : 'tabler.table' }}" class="btn btn-sm btn-outline"/>
    </div>
    @if ($view)
        <div>
            <x-card title="">
                <x-table :headers="$headers" :rows="$coupons" :sort-by="$sortBy" striped
                         with-pagination>
                    <x-slot:empty>
                        <x-icon name="o-cube" label="Kupon bulunmuyor."/>
                    </x-slot:empty>
                    @scope('cell_discount_type', $coupon)
                    {{ $coupon->discount_type ? 'YÜZDE' : 'TL'  }}
                    @endscope
                    @can('coupon_process')
                        @scope('actions', $offer)
                        <x-button icon="tabler.settings"
                                  wire:click="showSettings({{ $offer->id }})"
                                  class="btn-circle btn-sm btn-primary"/>
                        @endscope
                    @endcan
                </x-table>
            </x-card>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($coupons->count() == 0)
                <p class="text-center">Kupon bulunmuyor.</p>
            @endif
            @foreach ($coupons as $offer)
                <x-card title="{{ $offer->code }}" separator class="mb-2">
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Kupon Tipi
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->discount_type ? 'YÜZDE' : 'TL'  }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            İndirim
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->discount_amount  }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Adet
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->count  }}
                        </x-slot:actions>
                    </x-list-item>
                    <x-list-item :item="$offer">
                        <x-slot:value>
                            Oluşturan
                        </x-slot:value>
                        <x-slot:actions>
                            {{ $offer->user->name ?? 'SİSTEM'}}
                        </x-slot:actions>
                    </x-list-item>

                    @can('coupon_process')
                        <x-slot:menu>
                            <x-button icon="tabler.settings"
                                      wire:click="showSettings({{ $offer->id }})"
                                      class="btn-circle btn-sm btn-primary"/>
                        </x-slot:menu>
                    @endcan
                </x-card>
            @endforeach
        </div>
        <x-pagination :rows="$coupons"/>
    @endif
    @can('coupon_process')
        <livewire:components.drawers.drawer_offer wire:model="editing"/>
    @endcan
</div>

