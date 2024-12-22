<div>
    <x-slide-over title="Filtrele">
        <livewire:components.form.date_time wire:key="date-ff-{{ Str::random(10) }}" label="Tarih"
                                            wire:model="date_range"
                                            mode="range"/>
        <livewire:components.form.client_multi_dropdown wire:key="jdwcqc1cc-{{ Str::random(10) }}"
                                                        wire:model="select_client_id"/>
        <livewire:components.form.branch_multi_dropdown wire:key="jxdwccc2c-{{ Str::random(10) }}"
                                                        wire:model="branches"/>
        <livewire:components.form.transaction_type_multi_dropdown wire:key="jdw3chcccc-{{ Str::random(10) }}"
                                                                  wire:model="select_type_id"/>
        <livewire:components.form.kasa_multi_dropdown wire:key="jdc4dbccc-{{ Str::random(10) }}"
                                                      wire:model="select_kasa_id"/>
        <livewire:components.form.masraf_multi_dropdown wire:key="jdxcc6xz7cc-{{ Str::random(10) }}"
                                                        wire:model="select_masraf_id"/>
        <livewire:components.form.staff_multi_dropdown label="Oluşturan" wire:key="stffcvcx-{{ Str::random(10) }}"
                                                       wire:model="select_create_staff_id"/>
        <x-choices-offline label="Giriş - Çıkış" wire:key="stffc111vcx-{{ Str::random(10) }}" :options="$select_payment"
                           wire:model="select_payment_id" single/>
    </x-slide-over>
</div>
