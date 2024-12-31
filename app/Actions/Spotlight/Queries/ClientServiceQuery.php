<?php

namespace App\Actions\Spotlight\Queries;

use App\Models\ClientService;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class ClientServiceQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('clientService', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $clientServiceToken, $query) {
            $clientServicesList = ClientService::query()
                ->select('id', 'client_id', 'service_id', 'sale_id', 'status', 'remaining', 'total')
                ->where('client_id', '=', $clientToken->getParameter('id'))
                ->with('sale:id,sale_no,date', 'service:id,name')
                ->get();

            $clientServiceGroup = $clientServicesList->groupBy('status');
            $results = collect();

            $results->push(
                SpotlightResult::make()
                    ->setTitle('Geri Dön')
                    ->setGroup('backk')
                    ->setIcon('arrow-left')
                    ->setAction('return_action'),
            );

            $results->push(SpotlightResult::make()
                ->setTitle('Tümünü görüntüle')
                ->setGroup('actions')
                ->setIcon('check-circle')
                ->setAction('jump_to',
                    ['path' => route('admin.client.profil.index', ['user' => $clientToken->getParameter('id'), 'tab' => 'service']),
                    ]));

            foreach ($clientServiceGroup as $status => $clientServices) {
                foreach ($clientServices as $service) {
                    $results->push(SpotlightResult::make()
                        ->setTitle($service->service->name)
                        ->setSubtitle('Satış: '.$service->sale?->sale_no.' | Toplam: '.$service->total.' | Kalan: '.$service->remaining)
                        ->setGroup(($service->remaining == 0 ? 'finish' : $service->status->name))
                        ->setIcon('check-circle')
                    );
                }
            }

            return $results;
        });
    }
}
