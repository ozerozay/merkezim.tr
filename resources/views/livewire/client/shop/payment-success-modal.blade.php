<div class="overflow-x-hidden">
    <x-card title="Harika!" separator progress-indicator>

        <div class="max-w-sm w-full bg-base-200 shadow-md rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="flex items-center space-x-4">
                    <!-- Icon -->
                    <svg class="h-12 w-12 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-green-600">Ödemeniz Alındı!</h2>
                </div>
                <p class="text-lg mt-4">Sipariş numaranız: <span
                        class="font-semibold">#{{ $payment->unique_id }}</span></p>
                <p class="text-lg mt-4">Ödemeniz başarıyla alındı. Onaylandıktan sonra bildirilecektir.
                    Teşekkür ederiz.</p>
            </div>
        </div>

        <x-slot:menu>
            <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')"/>
        </x-slot:menu>
    </x-card>
</div>
