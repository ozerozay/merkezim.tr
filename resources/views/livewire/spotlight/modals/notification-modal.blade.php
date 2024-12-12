<div>
    <div class="overflow-x-hidden">
        <x-card title="Bildirimler" separator progress-indicator>
            @foreach ($notifications as $notification)
                <div class="card bg-base-{{ $notification->read_at ? '200' : '100' }} shadow-md rounded-lg">
                    <div class="card-body p-4">
                        <div class="flex items-start justify-between">
                            <!-- Bildirim İçeriği -->
                            <div>
                                <h2 class="font-medium text-lg">
                                    {{ \App\Enum\PermissionType::from($notification->data['type'])->label() }}</h2>
                                <p class="text-sm text-gray-600">{{ $notification->data['message'] }}</p>
                                <p class="text-sm text-gray-600">Tarih: {{ $notification->created_at }}</p>
                            </div>
                            <!-- Durum -->
                            <div class="badge badge-{{ $notification->read_at ? 'success' : 'warning' }}">
                                {{ $notification->read_at ? '' : 'Yeni' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <x-slot:menu>
                <x-button icon="tabler.x" class="btn-sm btn-outline" wire:click="$dispatch('slide-over.close')" />
            </x-slot:menu>
        </x-card>
    </div>
