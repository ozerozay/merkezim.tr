<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public function mount()
    {
        $this->success('burda');
    }
};
?>
<div>
<div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">

<x-stat
    title="Aktif Randevu"
    value="0"
    icon="o-calendar-days" />
    <x-stat
    title="Aktif Satış"
    value="0"
    icon="o-credit-card" />
    <x-stat
    title="Gecikmiş Ödeme"
    value="0 ₺"
     class="text-red-500"
    icon="o-credit-card" />
    <x-stat
    title="Açık Teklif"
    value="0"
    icon="tabler.confetti" />
      
</div>


</div>