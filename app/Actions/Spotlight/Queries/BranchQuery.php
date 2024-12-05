<?php

namespace App\Actions\Spotlight\Queries;

use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;

class BranchQuery
{
    use AsAction;

    public function handle()
    {
        return SpotlightQuery::forToken('branch', function ($query) {
            $results = collect();

            foreach (auth()->user()->staff_branch as $branch) {
                $results->push(SpotlightResult::make()
                    ->setTitle($branch->name)
                    ->setGroup('branches')
                    ->setTokens(['appointment_branch' => $branch, 'appointment_page' => new Appointment])
                    ->setIcon('building-storefront')
                );
            }

            $results = $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return $results;
        });
    }
}
