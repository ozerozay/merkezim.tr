<?php

namespace App\Livewire\Modals;

use App\Actions\Spotlight\Actions\Get\GetPackages;
use App\Models\Package;
use App\Models\User;
use Mary\Traits\Toast;
use WireElements\Pro\Components\Modal\Modal;

class SelectPackageModal extends Modal
{
    use Toast;

    public int|User $client;

    public $package_ids = [];

    public $gift = false;

    public int $quantity = 1;

    public $package_collection;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->package_collection = collect();
    }

    public function search(string $value = ''): void
    {
        $selectedOption = Package::whereIn('id', $this->package_ids)->get();
        $this->package_collection = GetPackages::run([$this->client->branch_id], $this->client->gender, $value)->merge($selectedOption);
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'package_ids' => $this->package_ids,
            'gift' => $this->gift,
            'quantity' => $this->quantity,
        ], [
            'package_ids' => 'required',
            'gift' => 'required|boolean',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        $this->success('Paket eklendi.');
        $this->dispatch('modal-package-added', $validator->validated());
        $this->package_ids = [];
        $this->reset('quantity', 'gift');
    }

    public static function behavior(): array
    {
        return [
            'close-on-escape' => true,
            'close-on-backdrop-click' => true,
            'trap-focus' => true,
            'remove-state-on-close' => true,
        ];
    }

    public function render()
    {
        return view('livewire.spotlight.modals.select-package-modal');
    }
}
