<div>
    <div class="overflow-x-hidden">
        <x-card title="Hizmet İşlemleri" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
            <x-accordion wire:model="group" separator class="bg-base-200">
                <x-collapse name="group0">
                    <x-slot:heading>
                        <x-icon name="tabler.toggle-right" label="Durum"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="changeStatus">
                            <livewire:components.form.active_cancel_status_dropdown wire:key="ccc-{{Str::random(10)}}"
                                                                                    wire:model="service_status"/>
                            <x-input label="Açıklama" wire:model="messageStatus"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="changeStatus" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Düzenle"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="edit">
                            <livewire:components.client.client_sale_dropdown wire:key="cxcc-{{Str::random(10)}}"
                                                                             wire:model="service_sale_id"
                                                                             :client_id="$client_id"/>
                            <livewire:components.form.number_dropdown wire:key="ccwc-{{Str::random(10)}}"
                                                                      wire:model="service_remaining" label="Kalan"
                                                                      :includeZero="true"/>
                            <livewire:components.form.number_dropdown wire:key="cttcc-{{Str::random(10)}}"
                                                                      wire:model="service_total" label="Toplam"
                                                                      :includeZero="true"/>
                            <livewire:components.form.service_dropdown wire:key="cxcacc-{{Str::random(10)}}"
                                                                       wire:model="service_service_id"
                                                                       :branch_id="$branch_id"/>
                            <x-input label="Açıklama" wire:model="messageEdit"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" spinner="edit" class="btn-primary"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil"/>
                    </x-slot:heading>
                    <x-slot:content>
                        <x-form wire:submit="delete">
                            <x-alert title="Emin misiniz ?" description="Hizmet silme işlemi geri alınamaz."
                                     icon="o-minus-circle" class="alert-error"/>
                            <x-input label="Açıklama" wire:model="messageDelete"/>
                            <x-slot:actions>
                                <x-button label="Gönder" type="submit" class="btn-error"/>
                            </x-slot:actions>
                        </x-form>
                    </x-slot:content>
                </x-collapse>
            </x-accordion>
        </x-card>
    </div>
</div>
