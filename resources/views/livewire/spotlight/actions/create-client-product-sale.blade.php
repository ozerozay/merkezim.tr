<div>
    <x-slide-over title="Ürün Sat" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.form.date_time
            wire:key="date-fix3ld-{{ Str::random(10) }}"
            label="Tarih"
            wire:model="date"
        />
        <livewire:components.form.staff_multi_dropdown
            wire:key="mccdasdf-{{ Str::random(10) }}"
            wire:model="staff_ids"/>
        @can(\App\Enum\PermissionType::change_sale_price)
            <x-input label="Satış Tutarı" hint="Boş bırakırsanız seçtiğiniz hizmet ve ürün toplamı hesaplanır."
                     wire:model="price" suffix="₺" money/>
        @endcan
        <x-input label="Satış notunuz" wire:model="message"/>
        <x-hr/>
        <livewire:spotlight.components.add-product-coupon-service-package
        :client="$client"
        :selected_services="$selected_services"
        :selected_payments="$selected_payments"
        :price="$price"
        :couponPrice="$couponPrice"
        :actives="['payment', 'coupon', 'product']"
         wire:key="$client->id" />
    </x-slide-over>
</div>
