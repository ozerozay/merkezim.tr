<div>
    <div class="overflow-x-hidden">
        <x-card title="Fatura Bilgileri" separator progress-indicator>
            <label wire:click="selectStandart"
                   class="bg-base-100 p-4 rounded-lg cursor-pointer border border-gray-600 flex items-center gap-4">
                <input type="radio" wire:model.live="paymentMethod" value="havale"
                       class="radio radio-primary hidden"/>
                <!-- Heroicon -->
                <x-icon name="tabler.paperclip"/>
                <!-- Metin -->
                <div class="flex-1">
                    <span class="text-lg font-medium">Standart eArşiv faturası istiyorum.</span>
                </div>
            </label>
            <x-hr/>
            <x-form wire:submit="selectTC">
                <x-input wire:model="fatura" label="Vergi No / TC Kimlik No"/>
                <x-slot:actions>
                    <x-button type="submit" label="Gönder" class="btn-block btn-primary"/>
                </x-slot:actions>
            </x-form>
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
            </x-slot:menu>
        </x-card>
    </div>
