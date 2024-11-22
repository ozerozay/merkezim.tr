<div>
    <x-wire-elements-pro::tailwind.slide-over on-submit="save" :content-padding="true">
        <x-slot name="title">Not Al</x-slot>
        <x-form wire:submit="save">
            <livewire:components.form.client_dropdown wire:model="client_id"/>
            <x-textarea label="Mesajınız" wire:model="message"/>
            <x-slot:actions>
                <x-button type="submit" spinner="save" class="btn-primary">
                    Kaydet
                </x-button>
                <x-button type="button" class="btn-error" wire:click="$dispatch('slide-over.close')">
                    İptal
                </x-button>
            </x-slot:actions>
        </x-form>
    </x-wire-elements-pro::tailwind.slide-over>
</div>
