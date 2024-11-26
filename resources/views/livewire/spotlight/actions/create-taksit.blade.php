<div>
    <x-slide-over title="Taksit Oluştur" subtitle="{{ $client->name ?? '' }}">
        <livewire:components.client.client_sale_dropdown
            wire:key="csdpd-{{ Str::random() }}"
            label="Satış - Zorunlu değil"
            wire:model="sale_id"
            :client_id="$client->id"/>
        <x-input label="Açıklama" wire:model="message"/>
        <x-button
            class="btn-outline"
            icon="tabler.plus"
            wire:click="$dispatch('modal.open', {component: 'modals.select-taksit-modal', arguments: {'client': {{ $client->id }}}})">
            Taksit Ekle
        </x-button>
        <x-hr/>
        @foreach ($selected_taksits as $taksit)
            <x-list-item :item="$taksit" no-separator no-hover>
                <x-slot:avatar>
                    <x-badge :value="$taksit['id']" class="badge-primary text-l indicator-item"/>
                </x-slot:avatar>
                <x-slot:value>
                    {{ $taksit['date'] }}
                </x-slot:value>
                <x-slot:sub-value>
                    @price($taksit['price'])
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-lock-closed"
                              wire:click="$dispatch('modal.open', {component: 'modals.select-taksit-service-modal', arguments: {'client': {{ $client->id }}, 'taksit': {{ $taksit['id'] }}}})"
                              class="text-blue-500" spinner
                              tooltip="Bu taksit ödendiğinde hangi hizmetler açılacak?">
                        <x-badge :value="count($taksit['locked'])" class="badge-primary indicator-item"/>
                    </x-button>
                    <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                              wire:click="deleteItem({{ $taksit['id'] }})" spinner="deleteItem({{ $taksit['id'] }})"/>
                </x-slot:actions>
            </x-list-item>
            @foreach($taksit['locked'] as $key=>$locked)
                <x-list-item :item="$locked" no-separator no-hover>
                    <x-slot:value>
                        {{ $locked['service_name'] }} ({{ $locked['quantity'] }})
                    </x-slot:value>
                    <x-slot:actions>
                        <x-button icon="o-trash" class="text-red-500" wire:confirm="Emin misiniz ?"
                                  wire:click="deleteLockedItem({{ $taksit['id'] }}, {{ $key }})"
                                  spinner="deleteLockedItem({{ $taksit['id'] }}, {{ $key }})"/>
                    </x-slot:actions>
                </x-list-item>
            @endforeach
        @endforeach
    </x-slide-over>
</div>
