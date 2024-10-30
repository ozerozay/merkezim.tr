<?php

new class extends \Livewire\Volt\Component {

    use \Mary\Traits\Toast;

    #[\Livewire\Attributes\Modelable]
    public bool $isOpen = false;

    public ?int $id = null;

    public ?string $start = null;

    public ?string $end = null;

    public bool $isLoading = false;

    #[\Livewire\Attributes\On('drawer-kasa-detail-update-info')]
    public function updateInfo($info): void
    {
        $this->id = $info['id'];
        $this->start = $info['start'];
        $this->end = $info['end'];
        $this->init();
    }

    public function init(): void
    {
        
    }
};

?>
