<div wire:key="{{Str::random(20)}}">
    <div class="px-4 sm:px-6 flex h-full flex-col">
        <div class="flex items-start justify-between">

            <h2 class="text-lg font-medium text-gray-900">Not Al</h2>

            <div class="ml-3 flex h-7 items-center">
                <button type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        wire:click="$dispatch('slide-over.close')">
                    <span class="sr-only">Kapat</span>
                    <svg class="h-6 w-6"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div @class(['relative mt-6 flex-1', 'px-4 sm:px-6' => true]) wire:key="{{Str::random(20)}}">
        <div @class(['absolute inset-0', 'px-4 sm:px-6' => true])>
            <x-form wire:submit="save" wire:key="{{Str::random(20)}}">
                <livewire:components.form.client_dropdown
                    wire:key="client-dropdown-sdfsadf332"
                    wire:model="client_id"/>
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
        </div>
    </div>

</div>
