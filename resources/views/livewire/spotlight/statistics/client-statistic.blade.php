<div>
    <x-header title="İstatistik - Danışan" separator progress-indicator>
        <x-slot:actions>
        </x-slot:actions>

    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-card title="Yaşlara Aralığına Göre">
            <x-chart wire:model="myChart" />
        </x-card>
        <x-card title="İlçelere Göre">
            <x-chart wire:model="myChart" />
        </x-card>
    </div>
</div>
