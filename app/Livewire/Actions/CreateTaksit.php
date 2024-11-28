<?php

namespace App\Livewire\Actions;

use App\Actions\Spotlight\Actions\Client\CreateTaksitAction;
use App\Enum\PermissionType;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use WireElements\Pro\Components\SlideOver\SlideOver;

class CreateTaksit extends SlideOver
{
    use Toast;

    public int|User $client;

    public ?Collection $selected_taksits;

    public $message = null;

    public $sale_id = null;

    public function mount(User $client): void
    {
        $this->client = $client;
        $this->selected_taksits = collect();
    }

    #[On('modal-taksit-added')]
    public function taksitAdded($info)
    {
        for ($i = 0; $i < $info['amount']; $i++) {
            $lastId = $this->selected_taksits->last() != null ? $this->selected_taksits->last()['id'] + 1 : 1;
            $this->selected_taksits->push([
                'id' => $lastId,
                'date' => Carbon::parse($info['first_date'])->addMonths($i)->format('d/m/Y'),
                'price' => $info['price'],
                'locked' => [],
            ]);
        }
    }

    #[On('modal-taksit-service-added')]
    public function taksitServiceAdded($info): void
    {
        $services = Service::whereIn('id', $info['service_ids'])->select('id', 'name')->get();

        $this->selected_taksits->transform(function ($item) use ($info, $services) {
            if ($item['id'] == $info['taksit']) {
                foreach ($services as $service) {
                    $item['locked'][] = [
                        'service_id' => $service->id,
                        'service_name' => $service->name,
                        'quantity' => $info['quantity'],
                    ];
                }
            }

            return $item;
        });
    }

    public function deleteLockedItem($taksit, $id): void
    {
        $this->selected_taksits->transform(function ($item) use ($taksit, $id) {
            if ($item['id'] == $taksit) {
                unset($item['locked'][$id]);
            }

            return $item;
        });
    }

    public function deleteItem($id): void
    {
        $this->selected_taksits = $this->selected_taksits->reject(function ($q) use ($id) {
            return $q['id'] == $id;
        });
    }

    public function save(): void
    {
        $validator = \Validator::make([
            'client_id' => $this->client->id,
            'sale_id' => $this->sale_id,
            'taksits' => $this->selected_taksits->toArray(),
            'message' => $this->message,
            'user_id' => auth()->user()->id,
            'permission' => PermissionType::action_client_create_taksit->name,
        ], [
            'client_id' => 'required|exists:users,id',
            'sale_id' => 'nullable|exists:sale,id',
            'taksits' => 'required|array',
            'message' => 'required',
            'user_id' => 'required|exists:users,id',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages()->first());

            return;
        }

        CreateTaksitAction::run($validator->validated());
        $this->success('Taksit oluşturuldu.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.spotlight.actions.create-taksit');
    }
}
