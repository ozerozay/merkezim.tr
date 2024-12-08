<div>
    <x-header title="{{ __('client.menu_referans') }}" subtitle="{{ __('client.page_referans_subtitle') }}" separator
        progress-indicator>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
        <x-card>
            <div class="mb-5" x-data="{
                link: '{{ route('client.index', ['ref' => auth()->user()->unique_id]) }}',
                copied: false,
                timeout: null,
                copy() {
                    $clipboard(this.link)
            
                    this.copied = true
            
                    clearTimeout(this.timeout)
            
                    this.timeout = setTimeout(() => {
                        this.copied = false
                    }, 3000)
                }
            }">
                <x-input label="Davet Adresiniz" value="{{ route('client.index', ['ref' => auth()->user()->unique_id]) }}"
                    readonly>
                    <x-slot:append>
                        <x-button x-on:click="copy" label="Kopyala" icon="tabler.copy"
                            class="btn-primary rounded-s-none" />
                    </x-slot:append>
                </x-input>
            </div>
            <div class="flex justify-between gap-3 items-center w-full">
                <x-button icon="tabler.device-mobile-message" class="btn btn-outline flex-grow" link="sms://"
                    external>SMS
                    ile
                    Paylaş</x-button>
                <x-button icon="tabler.brand-whatsapp" class="btn btn-outline flex-grow" external
                    link="whatsapp://">Whatsapp
                    ile Paylaş</x-button>
            </div>
        </x-card>
    </div>
</div>
