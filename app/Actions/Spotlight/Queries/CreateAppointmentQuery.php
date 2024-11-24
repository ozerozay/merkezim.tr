<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\Actions\Client\Get\GetClientServiceCategory;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class CreateAppointmentQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('appointment_create', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $appointmentToken, $query) {
            $service_categories = GetClientServiceCategory::run($clientToken->getParameter('id'));
            $results = collect();

            if ($service_categories->isEmpty()) {
                $results->push(SpotlightResult::make()
                    ->setTitle('Aktif hizmet kategorisi bulunamadı.')
                    ->setGroup('appointment_create')
                    ->setIcon('x-circle'));
            } else {
                foreach ($service_categories as $service_category) {
                    $sq = SpotlightResult::make()
                        ->setTitle($service_category->name)
                        ->setGroup('appointment_create')
                        ->setIcon('check-circle');
                    if ($appointmentToken->getParameter('id') == 1) {
                        $sq->setAction('dispatch_event',
                            ['name' => 'slide-over.open',
                                'data' => ['component' => 'actions.create-appointment-manuel',
                                    'arguments' => [
                                        'client' => $clientToken->getParameter('id'),
                                        'category' => $service_category->id,
                                    ]],
                            ]);
                    } elseif ($appointmentToken->getParameter('id') == 2) {
                        $sq->setAction('dispatch_event',
                            ['name' => 'slide-over.open',
                                'data' => ['component' => 'actions.create-appointment-range',
                                    'arguments' => [
                                        'client' => $clientToken->getParameter('id'),
                                        'category' => $service_category->id,
                                    ]],
                            ]);
                    }
                    $results->push($sq);
                }
            }

            return $results;
        });

    }
}
