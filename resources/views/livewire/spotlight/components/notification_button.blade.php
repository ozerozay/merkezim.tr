<div wire:key="jdsfkspd">
    <x-button wire:click="$dispatch('slide-over.open', {'component' : 'modals.notification-modal'})" icon="o-bell"
        class="btn-circle relative">
        @if (auth()->user()->unreadNotifications->isNotEmpty())
            <x-badge value="{{ auth()->user()->unreadNotifications->count() }}"
                class="badge-error absolute -right-2 -top-2" />
        @endif
    </x-button>

</div>
