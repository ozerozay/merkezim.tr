<div>
    <div class="overflow-x-hidden">
        <x-card title="Teklif İşlemleri" separator progress-indicator>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
            <x-accordion wire:model="group" separator class="bg-base-200">

                <x-collapse name="group1">
                    <x-slot:heading>
                        <x-icon name="o-pencil" label="Tarih Değiştir"/>
                    </x-slot:heading>
                    <x-slot:content>

                    </x-slot:content>
                </x-collapse>
                <x-collapse name="group2">
                    <x-slot:heading>
                        <x-icon name="o-minus-circle" label="Sil"/>
                    </x-slot:heading>
                    <x-slot:content>

                    </x-slot:content>
                </x-collapse>
            </x-accordion>
            <x-card title="Onayla" class="bg-base-200 mt-5">
                <x-form>
                    <livewire:components.form.kasa_dropdown wire:key="cmvkc-{{Str::random(10)}}"
                                                            wire:model="kasa_id"/>
                    <p>Kasaya tahsilat olarak işlenecektir.</p>
                    <x-slot:actions>
                        <x-button class="btn-block btn-outline" label="{{ $offer->price }} TL - Teklifi Onayla"/>
                    </x-slot:actions>

                </x-form>
            </x-card>
        </x-card>
    </div>
</div>
