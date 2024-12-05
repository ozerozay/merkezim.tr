<?php

new #[\Livewire\Attributes\Layout('components.layouts.client')] #[\Livewire\Attributes\Title('Seanslarım')] class extends \Livewire\Volt\Component
{
    use \App\Traits\WebSettingsHandler, \Mary\Traits\Toast;

    public bool $show_zero = false;

    public bool $show_category = false;

    public bool $add_seans = false;

    public $seans = [];

    public ?Collection $data;

    public function mount()
    {
        $this->show_zero = $this->getBool(\App\Enum\SettingsType::client_page_seans_show_zero->name);
        $this->show_category = $this->getBool(\App\Enum\SettingsType::client_page_seans_show_category->name);
        $this->add_seans = $this->getBool(\App\Enum\SettingsType::client_page_seans_add_seans->name);

        $this->data = \App\Actions\Spotlight\Actions\Web\GetPageSeansAction::run($this->show_category, $this->show_zero);
    }
};

?>

<div>
    <x-header title="Seanslarım" separator progress-indicator>
        <x-slot:actions>
            @if ($add_seans)
            <x-button class="btn-primary btn-sm" icon="o-plus">
                Seans Yükle
            </x-button>
            @endif
        </x-slot:actions>
    </x-header>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @if ($show_category)
        @foreach($data->groupBy('service_id') as $group)
        @endforeach
        @else
        @foreach($data as $service)
        <x-card title="{{ $service->service->name }}" separator class="mb-2 card w-full bg-base-100 cursor-pointer border">
            <x-slot:menu>
                <x-button icon="o-plus" class="btn-circle btn-outline btn-sm" />
            </x-slot:menu>
            <x-progress value="12" max="100" class="progress-warning h-3" />
            <x-list-item :item="$service">
                <x-slot:value>
                    Kategori
                </x-slot:value>
                <x-slot:actions>
                    {{ $service->service->category->name }}
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Kalan
                </x-slot:value>
                <x-slot:actions>
                    {{ $service->remaining }}
                </x-slot:actions>
            </x-list-item>
            <x-list-item :item="$seans">
                <x-slot:value>
                    Toplam
                </x-slot:value>
                <x-slot:actions>
                    {{ $service->total }}
                </x-slot:actions>
            </x-list-item>
        </x-card>
        @endforeach
        @endif
    </div>
</div>
