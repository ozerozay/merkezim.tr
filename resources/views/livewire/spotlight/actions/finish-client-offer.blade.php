<div>
    <x-slide-over title="Teklifi Tamamla" subtitle="{{ $offer->client->name }}">
        <!-- Form -->
        <livewire:components.form.kasa_dropdown wire:key="kasa-dropdown-{{ Str::random(10) }}" wire:model='kasa_id' />
        <x-input label="Tutar" wire:key="t-t-{{ Str::random(10) }}" wire:model="price" suffix="₺" money/>
        <x-input label="Açıklama" wire:key="kaasa-ax-{{ Str::random(10) }}" wire:model="message"/>
        <!-- Teklif Detayları -->
        <div class="border-t border-base-300 pt-4 mt-4 first:border-t-0 first:pt-0 first:mt-0">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <x-icon name="o-gift" class="w-4 h-4 text-primary" />
                    <span class="text-sm font-medium">Teklif #{{ $offer->unique_id }}</span>
                </div>
                <span class="badge badge-{{ $offer->status->color() }}">{{ $offer->status->label() }}</span>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm opacity-70">Teklif Tutarı</span>
                    <span class="text-sm font-medium">@price($offer->price)</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm opacity-70">Son Geçerlilik</span>
                    <span class="text-sm font-medium">{{ $offer->expire_date?->format('d.m.Y') ?? 'Süresiz' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm opacity-70">Oluşturan</span>
                    <span class="text-sm font-medium">{{ $offer->user->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm opacity-70">Oluşturulma</span>
                    <span class="text-sm font-medium">{{ $offer->created_at->format('d.m.Y H:i') }}</span>
                </div>
                
                @if($offer->items->isNotEmpty())
                    <div class="border-t border-base-300 pt-2 mt-2">
                        <p class="text-sm opacity-70 mb-1">Hizmetler</p>
                        <div class="space-y-1">
                            @foreach($offer->items as $item)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm">{{ $item->itemable->name }}</span>
                                    <span class="text-sm font-medium">{{ $item->quantity }} Seans</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        
    </x-slide-over>
</div>