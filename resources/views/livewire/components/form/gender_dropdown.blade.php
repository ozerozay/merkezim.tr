<?php

use Livewire\Attributes\Modelable;
use Livewire\Volt\Component;

new class extends Component
{
    #[Modelable]
    public $gender;

    public $genders;

    public $includeUniSex = true;

    public function mount()
    {
        $app = app();
        $object_erkek = $app->make('stdClass');
        $object_erkek->name = 'Erkek';
        $object_erkek->id = 2;

        $object_kadin = $app->make('stdClass');
        $object_kadin->name = 'Kadın';
        $object_kadin->id = 1;

        if ($this->includeUniSex) {
            $object_unisex = $app->make('stdClass');
            $object_unisex->name = 'Unisex';
            $object_unisex->id = 0;
        }

        $array = [$object_kadin, $object_erkek];

        if ($this->includeUniSex) {
            $array[] = $object_unisex;
        }

        $this->genders = collect($array);
    }
};
?>
<div>
<x-select label="Cinsiyet" icon="o-user" :options="$genders" wire:model="gender" />
</div>