<?php

use App\Traits\LiveHelper;

new class extends \Livewire\Volt\Component {

    public array $data = [];

    public int $column = 4;

}

?>

<div>
    @if (!empty($data))
        <div class="grid grid-cols-2 lg:grid-cols-{{ $column }} gap-4">
            @foreach($data as $d)
                <x-stat title="{{ $d['name'] }}"
                        value="{{ isset($d['number']) ? LiveHelper::price_text($d['value']) : $d['value']  }}"
                        icon="{{ $d['icon'] ?? 'o-chart-bar'}}"
                        class="{{ isset($d['red']) ? 'text-red-500' : '' }}"
                />
            @endforeach
        </div>
    @endif
</div>
