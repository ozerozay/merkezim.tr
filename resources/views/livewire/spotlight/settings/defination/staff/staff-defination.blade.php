<div>
    <x-slide-over title="Personel">
        <x-slot:menu>
            <x-button icon="tabler.plus" class="btn-sm btn-primary"
                wire:click="$dispatch('slide-over.open', {component: 'settings.defination.staff.staff-create'})" />
        </x-slot:menu>
        @if ($staffs->isEmpty())
            <p class="text-center">Personel bulunmuyor.</p>
        @endif
        @foreach ($staffs as $staff)
            <x-list-item :item="$staff" wire:key="ksdv-{{ $staff->id }}">
                <x-slot:avatar>
                    <x-badge class="{{ $staff->can_login ? 'badge-success' : 'badge-error' }}" />
                </x-slot:avatar>
                <x-slot:value>
                    {{ $staff->name }} - {{ $staff->roles()->first()?->name }}
                </x-slot:value>
                <x-slot:sub-value>
                    {{ $staff->branch_names() }}
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-pencil" class="btn-outline btn-sm"
                        wire:click="$dispatch('slide-over.open', {component: 'settings.defination.staff.staff-edit', arguments: {'staff': {{ $staff->id }}}})"
                        spinner />
                </x-slot:actions>
            </x-list-item>
        @endforeach
        <x-slot:actions>
            <x-pagination :rows="$staffs" />
        </x-slot:actions>
    </x-slide-over>
</div>
