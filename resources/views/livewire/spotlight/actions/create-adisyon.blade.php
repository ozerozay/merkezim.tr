<div>
    <x-slide-over title="Adisyon Oluştur" subtitle="{{ $client->name ?? '' }}">
        <div class="grid grid-cols-2 gap-2">
            <livewire:components.form.date_time wire:key="date-fieeeeld-{{ Str::random(10) }}" label="Tarih"
                wire:model="date" />
            @can(\App\Enum\PermissionType::change_sale_price)
                <x-input label="Adisyon Tutarı" hint="Boş bırakırsanız seçtiğiniz hizmet ve ürün toplamı hesaplanır."
                    wire:model.live.debounce.1000ms="price" suffix="₺" money />
            @endcan
        </div>

        <livewire:components.form.staff_multi_dropdown wire:key="mdasdf-{{ Str::random(10) }}" wire:model="staff_ids" />

        <x-input label="Açıklama" wire:model="message" />
        <x-hr />

        <livewire:spotlight.components.add-product-coupon-service-package :client="$client" :selected_services="$selected_services"
            :selected_payments="$selected_payments" :price="$price" :couponPrice="$couponPrice" :actives="['payment', 'coupon', 'service', 'package', 'product']" wire:key="$client->id" />

    </x-slide-over>
</div>
